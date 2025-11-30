<?php

declare(strict_types=1);

require_once '../model/Bid.php';
require_once '../model/Item.php';

class BidRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db) 
    {
        $this->db = $db;
    }





    public function addBid(Bid $bid) : void
    {
        $query = "INSERT INTO bid (item_id, bidder_id, bid_amount) VALUES (?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was a problem preparing the placebid query: " . $this->db->error);

        $itemId = $bid->getItemId();
        $bidderId = $bid->getBidderId();
        $bidAmount = $bid->getBidAmount();
        $statement->bind_param('iid', $itemId, $bidderId, $bidAmount);

        if (!$statement->execute())
            throw new Exception("Failed to place bid: " . $statement->error);

        $bidId = $this->db->insert_id;
        $bid->setBidId($bidId);

        $statement->close();
    }



    public function getToClaimBidsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetToClaimBidsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in preparing getToClaimBidsByUserId : " 
                                . $this->db->error);
        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getToClaimBidsByUserId : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $toClaimBids = [];
        foreach($rows as $row)
            $toClaimBids[] = ClaimItemCardDTO::fromArray($row);

        return $toClaimBids;
    }



    /**
     * Gets the active bids in the profile view (Active Bids)
     */
    public function getActiveBidsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetBidsByUserIdQuery();
        $statement = $this->db->prepare($query);

        if (!$statement) 
            throw new Exception("There was a problem preparing the getActiveBidsByUserId() query: " 
                                . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("getActiveBidsByUserId has an error: " . $statement->error);

        $results = $statement->get_result();
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $activeBidsOfUser = [];
        foreach ($rows as $row) 
            $activeBidsOfUser[] = BidRowDTO::fromArray($row); 
        
        return $activeBidsOfUser;
    }


    public function getPastBidsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetPastBidsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in preparing getPastBidsByUserId : " 
                                . $this->db->error);
        $statement->bind_param('ii', $userId, $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getPastBidsByUserId : {$statement->error}");

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $pastBids = [];
        foreach($rows as $row)
            $pastBids[] = HistoryItemCardDTO::fromArray($row, $userId);

        return $pastBids;
    }



    /**
     * This should have a name for who bid
     */
    public function getBidsByItemId(int $itemId) : array
    {
            $query = "SELECT * FROM bid WHERE `item_id` = ?";
            $statement = $this->db->prepare($query);

            if (!$statement)
                throw new Exception("There was an error preparing the getBidsByItemId query: " . $this->db->error);

            $statement->bind_param('i', $itemId);

            if (!$statement->execute())
                throw new Exception("Failed to do getBidsByItemId: " . $statement->error);

            $results = $statement->get_result();
            $rows = $results->fetch_all(MYSQLI_ASSOC);
            $statement->close();

            $bidsOfItem = [];
            foreach($rows as $row) 
                $bidsOfItem[] = Bid::fromArray($row);

            return $bidsOfItem;
    }



    public function getBidById(int $bidId) : ?Bid
    {
        $query = "SELECT * FROM bid WHERE bid_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed preparing the query for getBidById: " . $this->db->error);

        $statement->bind_param('i', $bidId);
        
        if (!$statement->execute())
            throw new Exception("Failed to get Bid by Id: " . $statement->error);

        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $statement->close();

        if (!$row)
            return null;
        return Bid::fromArray($row);
    }




    private function retrieveGetBidsByUserIdQuery() : string 
    {
        return "SELECT 
                    i.item_id,                       -- 2. itemId
                    i.title,                         -- 3. title
                    (
                        SELECT image_url 
                        FROM item_image 
                        WHERE item_id = i.item_id 
                        LIMIT 1
                    ) AS image_url,                  -- 4. imageUrl
                    i.auction_end,                   -- 5. auctionEnd
                    MAX(b.bid_amount) AS my_bid,     -- 6. myBid (Show their HIGHEST offer, not their first)
                    i.current_bid                    -- 7. currentBid (The actual highest price of the item)
                FROM 
                    bid b
                JOIN 
                    item i ON b.item_id = i.item_id
                WHERE 
                    b.bidder_id = ?                  
                    AND i.item_status = 'Active'     -- Only show ongoing auctions
                GROUP BY 
                    i.item_id                        -- CRITICAL: Collapses multiple bids into one row
                ORDER BY 
                    i.auction_end ASC;               -- Show items ending soonest first";
    }


    private function retrieveGetToClaimBidsByUserId() : string 
    {
        return "SELECT 
                    i.item_id,
                    i.title,
                    i.current_bid,  -- Maps to currentBid (Final Price)
                    i.seller_id,    -- Maps to sellerId
                    i.meetup_code,  -- Maps to meetupCode
                    (
                        SELECT image_url 
                        FROM item_image 
                        WHERE item_id = i.item_id 
                        LIMIT 1
                    ) AS image_url
                FROM 
                    item i
                WHERE 
                    i.buyer_id = ?                  -- The logged-in user (You are the Winner)
                    AND i.item_status = 'Awaiting Meetup' -- The specific status for winning items
                ORDER BY 
                    i.auction_end DESC;             -- Show most recent wins first";
    }



    private function retrieveGetPastBidsByUserId() : string 
    {
        return "SELECT 
                    i.item_id,
                    i.title,
                    i.current_bid,       
                    i.item_status,      
                    i.buyer_id,         
                    i.seller_id,        
                    i.category,          
                    i.auction_end,      
                    i.date_sold,         
                    (
                        SELECT image_url 
                        FROM item_image 
                        WHERE item_id = i.item_id 
                        LIMIT 1
                    ) AS image_url,
                    (
                        SELECT COUNT(*) 
                        FROM rating r 
                        WHERE r.item_id = i.item_id 
                        AND r.rater_id = ? 
                    ) AS has_rated

                FROM 
                    bid b
                JOIN 
                    item i ON b.item_id = i.item_id
                WHERE 
                    b.bidder_id = ?         
                    AND i.item_status IN ('Sold', 'Expired', 'Cancelled By Seller', 'Removed By Admin', 'Awaiting Meetup')

                GROUP BY 
                    i.item_id                
                ORDER BY 
                    COALESCE(i.date_sold, i.auction_end) DESC; -- Show most recent events first";
    }


    
}