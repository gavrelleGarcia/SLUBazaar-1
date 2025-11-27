<?php 

declare(strict_types=1);


class MessageRepository 
{
    private mysqli $db;


    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    public function addMessage(Message $message) : void 
    {
        $query = "INSERT INTO message (conversation_id, message_text, is_seller, created_at) 
                    values (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addMessage query. " . $this->db->error);

        $conversationId = $message->getConversationId();
        $messageText = $message->getMessageText();
        $isSeller = $message->isIsSeller() ? 1 : 0;
        $createdAt = $message->getCreatedAt()->format('Y-m-d H:i:s');
        $statement->bind_param('isis', $conversationId, $messageText, $isSeller, $createdAt);


        if (!$statement->execute())
            throw new Exception("Failed to add message : "  . $statement->error);

        $message->setMessageId($this->db->insert_id);
        $statement->close();
    }



    public function getMessagesByConversationId(int $conversationId) : array
    {
        $query = "SELECT * FROM message WHERE conversation_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an erorr in preparing the getMessagesByConversationId 
                                query: " . $this->db->error);

        $statement->bind_param('i', $conversationId);

        if(!$statement->execute())
            throw new Exception("Failed to getMessageByConversationId : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $messages = [];
        foreach ($rows as $row)
            $messages[] = Message::fromArray($row);

        return $messages;
    }
}