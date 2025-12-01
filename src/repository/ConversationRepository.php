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

    private function retrieveGetConversationsByUserId() : string 
    {
        return "SELECT 
                    c.conversation_id, c.status,
                    i.title AS item_title,
                    u.fname AS other_fname, u.lname AS other_lname,
                    m.message_text, m.created_at AS last_message_time, m.is_read, m.is_seller
                FROM conversation c
                JOIN item i ON c.item_id = i.item_id
                JOIN user u ON u.user_id = IF(c.buyer_id = ?, c.seller_id, c.buyer_id)
                JOIN message m ON m.message_id = (
                    SELECT message_id FROM message m2 
                    WHERE m2.conversation_id = c.conversation_id 
                    ORDER BY m2.created_at DESC LIMIT 1
                ) 
                WHERE c.buyer_id = ? OR c.seller_id = ?
                ORDER BY m.created_at DESC";
    }

    
    /**
     * Retrieves all conversations of a user.
     */
    public function getConversationsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetConversationsByUserId();
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
            $conversations[] = ConversationDTO::fromArray($row);

        return $conversations;
    }



    /**
     * Retrieves all active conversations of a user.
     */
    public function getActiveConversationsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetStatusConversationsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare the getActiveConversationByUserId query: " . $this->db->error);

        $status = Status::Active->value;
        $statement->bind_param('iiis', $userId, $userId, $userId, $status);

        if (!$statement->execute())
            throw new Exception("Failed to do getActiveConversationsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $activeConversationsOfUser = [];
        foreach($rows as $row)
            $activeConversationsOfUser[] = ConversationDTO::fromArray($row);

        return $activeConversationsOfUser;
    }

    /**
     * Retrieves all archived conversations of a user. 
     */
    public function getArchivedConversationsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetStatusConversationsByUserId();
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
            $archivedConversationsOfUser[] = ConversationDTO::fromArray($row);

        return $archivedConversationsOfUser;
    }




    private function retrieveGetStatusConversationsByUserId() : string 
    {
        return "SELECT 
                    c.conversation_id, c.status,
                    i.title AS item_title,
                    u.fname AS other_fname, u.lname AS other_lname,
                    m.message_text, m.created_at AS last_message_time, m.is_read, m.is_seller
                FROM conversation c
                JOIN item i ON c.item_id = i.item_id
                JOIN user u ON u.user_id = IF(c.buyer_id = ?, c.seller_id, c.buyer_id)
                JOIN message m ON m.message_id = (
                    SELECT message_id FROM message m2 
                    WHERE m2.conversation_id = c.conversation_id 
                    ORDER BY m2.created_at DESC LIMIT 1
                ) 
                WHERE (c.buyer_id = ? OR c.seller_id = ?) AND c.status = ?
                ORDER BY m.created_at DESC";
    }



}