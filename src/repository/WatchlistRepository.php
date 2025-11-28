<?php

declare(strict_types=1);

require_once '../model/Watchlist.php';
require_once '../dto/WatchlistDetailsDTO.php';


class WatchlistRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }



    /**
     * This will be triggered if the user adds an item to his watchlist when viewing the full details
     * of the item
     */
    public function addWatchlist(Watchlist $watchlist) : void
    {
        $query = "INSERT INTO watchlist (user_id, item_id, created_at) values (?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in addWatchlist query: " . $this->db->error);

        $userId = $watchlist->getUserId();
        $itemId = $watchlist->getItemId();
        $createdAt = $watchlist->getCreatedAt()->format('Y-m-d H:i:s');
        $statement->bind_param('iis', $userId, $itemId, $createdAt);

        if(!$statement->execute())
            throw new Exception("Failed to add watchlist : " . $statement->error);

        $statement->close();
    }


    /**
     * This will be triggered if the user removes a watchlist item in his 'My Watchlist' section
     */
    public function removeWatchlist(int $watchlistId) : void
    {
        $query = "DELETE from watchlist WHERE watchlist_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in removeWatchlist query: " . $this->db->error);

        $statement->bind_param('i', $watchlistId);

        if(!$statement->execute())
            throw new Exception("Failed to remove watchlist : " . $statement->error);

        $statement->close();
    }



    /**
     * This will be triggered when a user goes to his "My Watchlist" section
     */
    public function getWatchlistByUserId(int $userId) : array 
    {
        $query = "SELECT 
                w.watchlist_id,
                w.created_at as added_at,
                i.item_id,
                i.title,
                i.current_bid,
                i.auction_end,
                -- SENIOR TECHNIQUE: Correlated Subquery
                -- It fetches exactly ONE image per item to avoid duplicate rows
                (
                    SELECT image_url 
                    FROM item_image 
                    WHERE item_id = i.item_id 
                    ORDER BY image_id ASC -- Get the first image uploaded
                    LIMIT 1 
                ) as image_url
            FROM watchlist w
            JOIN item i ON w.item_id = i.item_id
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC";

        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in getWatchlistByUserId query : " . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getWatchlistByUserId : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $watchlists = [];
        foreach($rows as $row)
            $watchlists[] = WatchlistItemDTO::fromArray($row);

        return $watchlists;
    }



}