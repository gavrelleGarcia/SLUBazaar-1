<?php

declare(strict_types=1);

enum Status : string
{
    case Active = 'Active';
    case Archived = 'Archived';
}

class Conversation 
{
    
    private ?int $conversationId;
    private int $itemId;
    private int $buyerId;
    private int $sellerId;
    private Status $status;


    public function __construct(?int $conversationId, int $itemId, int $buyerId, 
                                int $sellerId, Status $status) 
    {
        $this->conversationId = $conversationId;
        $this->itemId = $itemId;
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->status = $status;
    }


    public static function fromArray(array $conversation) : self
    {
        $status = $conversation['status'];
        return new self(
            (int)$conversation['conversation_id'],
            (int)$conversation['item_id'],
            (int)$conversation['buyer_id'],
            (int)$conversation['seller_id'],
            Status::from($conversation['status'])
        );
    }


    public function getConversationId() : ?int 
    {
        return $this->conversationId;
    }


    public function setConversationId(int $conversationId) : self 
    {
        $this->conversationId = $conversationId;
        return $this;
    }


    public function getItemId() : int 
    {
        return $this->itemId;
    }


    public function setItemId(int $itemId) : self
    {
        $this->itemId = $itemId;
        return $this;
    }


    public function getBuyerId() : int 
    {
        return $this->buyerId;
    }


    public function setBuyerId(int $buyerId) : self
    {
        $this->buyerId = $buyerId;
        return $this;
    }


    public function getSellerId() : int
    {
        return $this->sellerId;
    }


    public function setSellerId(int $sellerId) : self 
    {
        $this->sellerId = $sellerId;
        return $this;
    }


    public function getStatus() : Status 
    {
        return $this->status;
    }


    public function setStatus(Status $status) : self
    {
        $this->status = $status;
        return $this;
    }

    

}

