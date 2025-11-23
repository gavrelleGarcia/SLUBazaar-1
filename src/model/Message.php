<?php
declare(strict_types=1);

class Message 
{
    private int $messageId;
    private int $conversationId;
    private string $messageText;
    private bool $isSeller;
    private DateTimeImmutable $createdAt;

    public function _construct(int $messageId, int $conversationId, string $messageText, bool $isSeller)
    {
        $this->messageId = $messageId;
        $this->conversationId = $conversationId;
        $this->messageText = $messageText;
        $this->isSeller = $isSeller;
        $this->createdAt = new DateTimeImmutable();
    }
    

    /**
     * Get the value of messageId
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * Set the value of messageId
     */
    public function setMessageId(int $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get the value of conversationId
     */
    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    /**
     * Set the value of conversationId
     */
    public function setConversationId(int $conversationId): self
    {
        $this->conversationId = $conversationId;

        return $this;
    }

    /**
     * Get the value of messageText
     */
    public function getMessageText(): string
    {
        return $this->messageText;
    }

    /**
     * Set the value of messageText
     */
    public function setMessageText(string $messageText): self
    {
        $this->messageText = $messageText;

        return $this;
    }

    /**
     * Get the value of isSeller
     */
    public function isIsSeller(): bool
    {
        return $this->isSeller;
    }

    /**
     * Set the value of isSeller
     */
    public function setIsSeller(bool $isSeller): self
    {
        $this->isSeller = $isSeller;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }






    
}