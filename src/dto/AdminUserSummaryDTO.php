<?php

declare(strict_types=1);


class AdminUserSummaryDTO implements JsonSerializable
{
    public function __construct(
        public readonly string $fname,
        public readonly string $lname, 
        public readonly string $email, 
        public readonly float $averageRating,
        public readonly DateTimeImmutable $createdAt,
        public readonly string $accountStatus
    )
    {}



    public static function fromArray(array $data) : self
    {
        return new self(
            $data['fname'], 
            $data['lname'], 
            $data['email'], 
            $data['average_rating'],
            $data['created_at'],
            $data['account_status']
        );
    }



    public function jsonSerialize(): array
    {
        return [
            'fname' => $this->fname,
            'lname'      => $this->lname,
            'email'        => $this->email,
            'averageRating'  => $this->averageRating,
            'createdAt'  => $this->createdAt,
            'accountStatus'       => $this->accountStatus
        ];
    }



}