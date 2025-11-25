<?php

declare(strict_types=1);


require '../model/Conversation.php';

class ConversationRepository 
{

    private mysqli $db;

    public function _construct(mysqli $db)
    {
        $this->db = $db;
    }
    

    public function addConversation(Conversation $convo) : void 
    {
        $itemId = $convo->getItemId();
        $buyerId = $convo->getBuyerId();
        $sellerId = $convo->getSellerId();
        $status = $convo->getStatus()->value;

        $query = "INSERT INTO conversation (item_id, buyer_id, seller_id, status) values (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement) 
            throw new Exception ("There was a problem preparing the query: " . $this->db->error);

        $statement->bind_param('iiis', $itemId, $buyerId, $sellerId, $status);

        if (!$statement->execute()) 
            throw new Exception("Adding a conversation failed: " . $statement->error);

        $statement->close();
    }



    public function archivebyItemId(int $itemId) 
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




}