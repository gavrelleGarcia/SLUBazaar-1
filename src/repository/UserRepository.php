<?php

declare(strict_types=1);


require_once '../model/User.php';
require_once '../dto/AdminUserTableRowDTO.php';

class UserRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }



    /**
     * This will be triggered when a user registers ofc
     */
    public function addUser(User $user) : void
    {
        $query = "INSERT INTO user(fname, lname, email, password_hash, created_at) 
                    values (?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addUser query: " . $this->db->error);

        $fname = $user->getFirstName();
        $lname = $user->getLastName();
        $email = $user->getEmail();
        $passwordHash = $user->getPasswordHash();
        $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $statement->bind_param('sssss', $fname, $lname, $email, $passwordHash, $createdAt);

        if (!$statement->execute())
            throw new Exception("Failed to Add a User : " . $statement->error);

        $user->setUserId($this->db->insert_id);
        $statement->close();
    }



    /**
     * This will be triggered if a user is banned by the mods or admin 
     */
    public function updateAccountStatus(int $userId, AccountStatus $newStatus) : void
    {
        $query = "UPDATE user SET account_status = ? WHERE user_id = ? ";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the updateAccountStatus query. " 
                                . $this->db->error);
        $newAccountStatus  = $newStatus->value;
        $statement->bind_param('si', $newAccountStatus, $userId);

        if (!$statement->execute())
            throw new Exception("Failed to update the acount status : " . $statement->error);

        $statement->close();
    }



    public function getUserById(int $userId) : ?User
    {
        $query = "SELECT * FROM user WHERE user.user_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the getUserById query: " . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getUserById : " . $statement->error);

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        if (!$row)
            return null;

        return User::fromArray($row);
    }


    /**
     * This will be triggered when one user rates the item after a transaction
     */
    public function updateAverageRating(int $userId) : void
    {
        $query = "
            UPDATE user 
            SET average_rating = 
            (
                SELECT COALESCE(AVG(rating_value), 0) 
                FROM rating 
                WHERE ratee_id = ?
            ) 
            WHERE user_id = ?
        ";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the updateAverageRatingQuery : " 
                                . $this->db->error);

        $statement->bind_param('ii', $userId, $userId);

     
     
        if (!$statement->close())
            throw new Exception("Failed to update average rating: " . $statement->error);

        $statement->close();
    }



    /**
     * The user will use this when he is in his profile section : edit profile
     */
    public function updateName(int $userId, string $newFname, string $newLname) : void
    {
        $query  = "UPDATE user SET fname = ?, lname = ? WHERE user_id = ? ";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the updateName query : " . $this->db->error);

        $statement->bind_param('ssi', $newFname, $newLname, $userId);

        if (!$statement->execute())
            throw new Exception("Failed to update name : " . $statement->error);

        $statement->close();
    }



    /**
     * The admin will use this in his dashboad
     */
    public function getAllUsers() : array
    {
        $query = $this->getUserRetrievalQuery();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There is an error preparing the getAllUsers query. ");

        if (!$statement->execute())
            throw new Exception("Failed to get all users. " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $users = [];
        foreach($rows as $row)
            $users[] = AdminUserTableRowDTO::fromArray($row);

        return $users;
    }


    public function getAllActiveUsers() : array
    {
        $query = $this->getUserRetrievalQuery() . " WHERE account_status = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There is an error preparing the getAllActiveUsers query. ");

        $status = AccountStatus::Active->value;
        $statement->bind_param('s', $status);

        if (!$statement->execute())
            throw new Exception("Failed to get all active users. " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $activeUsers = [];
        foreach($rows as $row)
            $activeUsers[] = AdminUserTableRowDTO::fromArray($row);

        return $activeUsers;
    }




    public function getAllBannedUsers() : array 
    {
        $query = $this->getUserRetrievalQuery() . " WHERE account_status = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There is an error preparing the getAllBannedUsers query. ");

        $status = AccountStatus::Banned->value;
        $statement->bind_param('s', $status);

        if (!$statement->execute())
            throw new Exception("Failed to get all banned users. " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $bannedUsers = [];
        foreach($rows as $row)
            $bannedUsers[] = AdminUserTableRowDTO::fromArray($row);

        return $bannedUsers;
    }


    public function getUserRetrievalQuery() : string
    {
        return "SELECT 
                user.fname, user.lname, user.email, 
                user.average_rating, user.created_at, 
                user.account_status 
                FROM `user`";
    }

    

}