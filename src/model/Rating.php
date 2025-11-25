<?php

declare(strict_types=1);

class Rating 
{
    private int $ratingId;
    private int $itemId;
    private int $raterId;
    private int $rateeId;
    private int $ratingValue;
    private string $comment;
    private DateTimeImmutable $createdAt;

    public function __construct(int $itemId, int $raterId, int $rateeId, int $ratingValue, string $comment) 
    {
        $this->itemId = $itemId;
        $this->raterId = $raterId;
        $this->rateeId = $rateeId;
        $this->ratingValue = $ratingValue;
        $this->comment = $comment;
        $this->createdAt = new DateTimeImmutable();
    }


    

    /**
     * Get the value of ratingId
     */
    public function getRatingId(): int
    {
        return $this->ratingId;
    }

    /**
     * Set the value of ratingId
     */
    public function setRatingId(int $ratingId): self
    {
        $this->ratingId = $ratingId;

        return $this;
    }

    /**
     * Get the value of itemId
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * Set the value of itemId
     */
    public function setItemId(int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get the value of raterId
     */
    public function getRaterId(): int
    {
        return $this->raterId;
    }

    /**
     * Set the value of raterId
     */
    public function setRaterId(int $raterId): self
    {
        $this->raterId = $raterId;

        return $this;
    }

    /**
     * Get the value of rateeId
     */
    public function getRateeId(): int
    {
        return $this->rateeId;
    }

    /**
     * Set the value of rateeId
     */
    public function setRateeId(int $rateeId): self
    {
        $this->rateeId = $rateeId;

        return $this;
    }

    /**
     * Get the value of ratingValue
     */
    public function getRatingValue(): int
    {
        return $this->ratingValue;
    }

    /**
     * Set the value of ratingValue
     */
    public function setRatingValue(int $ratingValue): self
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    /**
     * Get the value of comment
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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