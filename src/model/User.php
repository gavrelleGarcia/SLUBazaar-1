<?php

declare(strict_types=1);

enum AccountStatus : string
{
    case Active = 'Active';
    case Unverified = 'Unverified';
    case Banned = 'Banned';
}

class User 
{
    private int $userId;
    private string $firstName;
    private string $lastName;
    private string $email;
    private bool $emailVerified;
    private string $passwordHash;
    private DateTimeImmutable $createdAt;
    private float $averageRating;
    private AccountStatus $accountStatus;

    public function _construct(int $userId, string $firstName, string $lastName, string $email, bool $emailVerified, 
                                string $passwordHash, DateTimeImmutable $createdAt, float $averageRating, 
                                AccountStatus $accountStatus) 
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->emailVerified = $emailVerified;
        $this->passwordHash = $passwordHash;
        $this->createdAt = $createdAt;
        $this->averageRating = $averageRating;
        $this->accountStatus = $accountStatus;
    }


    public function getUserId() : int 
    {
        return $this->userId;
    }

    public function setUserId(int $userId) : self
    {
        $this->userId = $userId;
        return $this;
    }


    public function getFirstName() : string 
    {
        return $this->firstName;
    }


    public function setFirstName(string $firstName) : self 
    {
        $this->firstName = $firstName;
        return $this;
    }


    public function getLastName() : string 
    {
        return $this->lastName;
    }


    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;
        return $this;
    }


    public function getEmail() : string 
    {
        return $this->email;
    }


    public function setEmail(string $email) : self 
    {
        $this->email = $email;
        return $this;
    }


    public function getEmailVerified() : bool 
    {
        return $this->emailVerified;
    }


    public function setEmailVerified(bool $emailVerified) : self
    {
        $this->emailVerified = $emailVerified;
        return $this;
    }


    public function getPasswordHash() : string
    {
        return $this->passwordHash;
    }


    public function setPasswordHash(string $passwordHash) : self 
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }


    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function getAverageRating() : float
    {
        return $this->averageRating;
    }


    public function setAverageRating(float $averageRating) : self 
    {
        $this->averageRating = $averageRating;
        return $this;
    }


    public function getAccountStatus() : AccountStatus 
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(AccountStatus $accountStatus) : self
    {
        $this->accountStatus = $accountStatus;
        return $this;
    }
}