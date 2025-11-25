<?php

declare(strict_types=1);

class Watchlist 
{
    private int $watchlistId;
    private int $userId;
    private int $itemId;

    public function __construct(int $userId, int $itemId)
    {
        $this->userId = $userId;
        $this->itemId = $itemId;
    }



    /**
     * Get the value of watchlistId
     */
    public function getWatchlistId(): int
    {
        return $this->watchlistId;
    }

    /**
     * Set the value of watchlistId
     */
    public function setWatchlistId(int $watchlistId): self
    {
        $this->watchlistId = $watchlistId;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

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
}