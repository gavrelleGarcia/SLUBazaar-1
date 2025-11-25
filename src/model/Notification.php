<?php

declare(strict_types=1);

class Notification 
{
    private int $notifId;
    private int $userId;
    private string $notifTitle;
    private string $content;
    private string $notifType;
    private DateTimeImmutable $notifTime;

    public function __construct(int $userId, string $notifTitle, string $content, string $notifType) 
    {
        $this->userId = $userId;
        $this->notifTitle = $notifTitle;
        $this->content = $content;
        $this->notifType = $notifType;
        $this->notifTime = new DateTimeImmutable();
    }


    public function getNotifId() : int 
    {
        return $this->notifId;
    }


    public function setNotifId(int $notifId) : self
    {
        $this->notifId = $notifId;
        return $this;
    }


    public function setUserId(int $userId) : self 
    {
        $this->userId = $userId;
        return $this;
    }


    public function getUserId() : int 
    {
        return $this->userId;
    }


    public function getNotifTitle() : string 
    {
        return $this->notifTitle;
    }


    public function setNotifTitle(string $notifTitle) : self 
    {
        $this->notifTitle = $notifTitle;
        return $this;
    }


    public function getContent() : string
    {
        return $this->content;
    }


    public function setContent(string $content) : self 
    {
        $this->content = $content;
        return $this;
    }


    public function getNotifType() : string 
    {
        return $this->notifType;
    }


    public function setNotifType(string $notifType) : self 
    {
        $this->notifType = $notifType;
        return $this;
    }



    /**
     * Get the value of notifTime
     */
    public function getNotifTime(): DateTimeImmutable
    {
        return $this->notifTime;
    }

    /**
     * Set the value of notifTime
     */
    public function setNotifTime(DateTimeImmutable $notifTime): self
    {
        $this->notifTime = $notifTime;

        return $this;
    }
}