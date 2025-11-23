<?php

declare(strict_types=1);

class Notification 
{
    private int $notifId;
    private int $userId;
    private string $notifTitle;
    private string $content;
    private string $notifType;

    public function _construct(int $notifId, int $userId, string $notifTitle, string $content, string $notifType) 
    {
        $this->notifId = $notifId;
        $this->userId = $userId;
        $this->notifTitle = $notifTitle;
        $this->content = $content;
        $this-> notifType = $notifType;
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

    


}