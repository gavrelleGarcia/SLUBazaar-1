<?php

declare(strict_types=1);


require_once '../model/User.php';

class UserRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }



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
        $createdAt = $user->getCreatedAt();
        $statement->bind_param('sssss', $fname, $lname, $email, $passwordHash, $createdAt);

        if (!$statement->execute())
            throw new Exception("Failed to Add a User : " . $statement->error);

        $user->setUserId($this->db->insert_id);
        $statement->close();
    }



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
}