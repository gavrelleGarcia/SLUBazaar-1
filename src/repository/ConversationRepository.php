<?php

declare(strict_types=1);


require_once '../model/Conversation.php';

class ConversationRepository 
{

    private mysqli $db;

    public function _construct(mysqli $db)
    {
        $this->db = $db;
    }
    

    /**
     * Adds a conversation in the database.
     */
    public function addConversation(Conversation $conversation) : void 
    {
        $itemId = $conversation->getItemId();
        $buyerId = $conversation->getBuyerId();
        $sellerId = $conversation->getSellerId();
        $status = $conversation->getStatus()->value;

        $query = "INSERT INTO conversation (item_id, buyer_id, seller_id, status) values (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement) 
            throw new Exception ("There was a problem preparing the query: " . $this->db->error);

        $statement->bind_param('iiis', $itemId, $buyerId, $sellerId, $status);

        if (!$statement->execute()) 
            throw new Exception("Adding a conversation failed: " . $statement->error);

        $conversationId = $this->db->insert_id;
        $conversation->setConversationId($conversationId);

        $statement->close();
    }



    /**
     * Archives a conversation. 
     */
    public function archiveByItemId(int $itemId) : void
    {
        $status = Status::Archived->value;
        $statement = $this->db->prepare("UPDATE conversation SET status = ? WHERE `item_id` = ?");

        if (!$statement) 
            throw new Exception("There was a problem preparing the data: " . $this->db->error);

        $statement->bind_param('si', $status, $itemId);

        if (!$statement->execute())
            throw new Exception("Archiving an item by id failed: " . $statement->error);

        $statement->close();
    }


    
    /**
     * Retrieves all conversations of a user.
     */
    public function getConversationsByUserId(int $userId) : array
    {
        $query = "SELECT * FROM conversation WHERE buyer_id = ? OR seller_id = ?"; // arrange this by last message
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare getConversationsByUserId query. " . $this->db->error);

        $statement->bind_param('ii', $userId, $userId);
        
        if (!$statement->execute())
            throw new Exception("getConversationsByUserId failed. " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $conversations = [];

        foreach ($rows as $row)
            $conversations[] = Conversation::fromArray($row);

        return $conversations;
    }



    /**
     * Retrieves all active conversations of a user.
     */
    public function getActiveConversationsByUserId(int $userId) : array
    {
        $query = "SELECT * FROM conversation WHERE (buyer_id = ? OR seller_id = ?) AND `status` = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare the getActiveConversationByUserId query: " . $this->db->error);

        $status = Status::Active->value;
        $statement->bind_param('iii', $userId, $userId, $status);

        if (!$statement->execute())
            throw new Exception("Failed to do getActiveConversationsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $activeConversationsOfUser = [];
        foreach($rows as $row)
            $activeConversationsOfUser[] = Conversation::fromArray($row);

        return $activeConversationsOfUser;
    }



    /**
     * Retrieves all archived conversations of a user. 
     */
    public function getArchivedConversationsByUserId(int $userId) : array
    {
        $query = "SELECT * FROM conversation WHERE (buyer_id = ? OR seller_id = ?) AND `status` = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare the getArchivedConversationByUserId query: " . $this->db->error);

        $status = Status::Archived->value;
        $statement->bind_param('iii', $userId, $userId, $status);

        if (!$statement->execute())
            throw new Exception("Failed to do getArchivedConversationsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $archivedConversationsOfUser = [];

        foreach($rows as $row)
            $archivedConversationsOfUser[] = Conversation::fromArray($row);

        return $archivedConversationsOfUser;
    }



    /**
     * Retrieves one conversation of a user through id. 
     */
    public function getConversationById(int $conversationid) : ?Conversation
    {
        $query = "SELECT * FROM conversation WHERE `conversation_id` = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare the getConversationById query: " . $this->db->error);

        $statement->bind_param('i', $conversationId);

        if (!$statement->execute())
            throw new Exception("Failed to getConversationById " . $statement->error);

        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $statement->close();

        if ($row)
            return null;

        return Conversation::fromArray($row);
    }



}