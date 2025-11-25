<?php

declare(strict_types=1);

require '../model/Bid.php';
require '../model/Item.php';

class BidRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db) 
    {
        $this->db = $db;
    }




    public function getBidsByUserId(int $userId) {
        $query = "SELECT * FROM bid WHERE `bidder_id` = ?";
        $statement = $this->db->prepare($query);

        if (!$statement) 
            throw new Exception("There was a problem preparing the getBidsByUserId() query: " . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("getBidsByUserId has an error: " . $statement->error);

        $statement->close();
    }


    public function getBidsByItemId(int $itemId) {
            $query = "SELECT * FROM bid WHERE `item_id` = ?";
            $statement = $this->db->prepare($query);

            if (!$statement)
                throw new Exception("There was an error preparing the getBidsByItemId query: " . $this->db->error);

            $statement->bind_param('i', $itemId);

            if (!$statement->execute())
                throw new Exception("Failed to do getBidsByItemId: " . $statement->error);

            $statement->close();
    }


    public function getActiveBidsByUserId(int $userId) : array {
        $query = "SELECT bid.* FROM bid
                  JOIN item ON bid.item_id = item.item_id
                  WHERE bid.bidder_id = ? 
                  AND item.item_status IN (?, ?)
                  ORDER BY bid.bid_timestamp DESC";
        $statement = $this->db->prepare($query);

        if (!$statement) 
            throw new Exception("There was an error preparing the getActiveBidsByUserId query : " . $this->db->error);

        $activeStatus = ItemStatus::Active->value;
        $awaitingMeetupStatus = ItemStatus::AwaitingMeetup->value;
        $statement->bind_param('iss', $userId, $activeStatus, $awaitingMeetupStatus);

        if (!$statement->execute())
            throw new Exception("Failed to do the getActiveBidsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $activeBids = [];
        foreach($rows as $row)
            $activeBids[] = Bid::fromArray($row);

        return $activeBids;
    }


    public function placeBid(Bid $bid)
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



        $statement->close();
    }



    
}