<?php

declare(strict_types=1);

require_once '../model/Item.php';
require_once '../dto/SearchItemsRequestDTO.php';
require_once '../dto/ItemRowDTO.php';

class ItemRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    public function addItem(Item $item) : void
    {
        $query = "INSERT INTO item(seller_id, title, description, starting_bid, current_bid, 
                                    created_at, auction_start, auction_end, item_status, category) 
                                values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Preparing the add item query failed : " . $this->db->error);

        $sellerId = $item->getSellerId();
        $title = $item->getTitle();
        $description = $item->getDescription();
        $startingBid = $item->getStartingBid();
        $currentBid = $item->getCurrentBid();

        $createdAt = $item->getCreatedAt()->format('Y-m-d H:i:s');
        $auctionStart = $item->getAuctionStart()->format('Y-m-d H:i:s');
        $auctionEnd = $item->getAuctionEnd()->format('Y-m-d H:i:s');
        
        $itemStatus = $item->getItemStatus()->value;
        $category = $item->getCategory()->value;

        $statement->bind_param('issddsssss', $sellerId, $title, $description, $startingBid, 
                                $currentBid, $createdAt, $auctionStart, $auctionEnd, 
                                $itemStatus, $category);
        
        if (!$statement->execute())
            throw new Exception("Failed to add item " . $statement->error);

        $item->setItemId($this->db->insert_id);
        $statement->close();
    }


    /**
     * Gets the full details of the item
     */
    public function getItemById(int $itemId) : ?Item 
    {
        $query = "SELECT * FROM item WHERE item_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Preparing the getItemById query failed: " . $this->db->error);

        $statement->bind_param('i', $itemId);

        if (!$statement->execute())
            throw new Exception("Failed to get Item by Id: " . $statement->error);

        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $statement->close();

        if (!$row)
            return null;

        return Item::fromArray($row);
    }



    public function getToHandoverItemsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetToHandoverItemsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the getToHandoverItemsByUserId : "
                                . $this->db->error);
        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getToHandoverItemsByUserId : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $toHandoverItems = [];
        foreach($rows as $row)
            $toHandoverItems[] = ToHandoverItemCardDTO::fromArray($row);

        return $toHandoverItems;
    }



    public function getActiveItemsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetActiveItemsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was a problem in preparing the getActiveItemsByUserId query : " 
                                . $this->db->error);
        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getActiveItemsByUserID : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $activeItems = [];
        foreach($rows as $row)
            $activeItems[] = ItemRowDTO::fromArray($row);

        return $activeItems;
    }



    public function getSoldItemsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetSoldItemsByUserIdQuery();
        $statement = $this->db->prepare($query);
        if (!$statement)
            throw new Exception("There was an error in preparing the getSoldItemsByUserId: " 
                                . $this->db->error);
        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getSoldItemsByUserId : " . $statement->error);

        $results = $statement->get_result();
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $soldItems = [];
        foreach($rows as $row)
            $soldItems[] = SoldItemCardDTO::fromArray($row);
        return $soldItems;
    }


    public function getUnsoldItemsByUserId(int $userId) : array
    {
        $query = $this->retrieveGetUnsoldItemsByUserIdQuery();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the getUnsoldItemsByUserId : " . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getUnsoldItemsByUserId : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $unsoldItems = [];
        foreach($rows as $row)
            $unsoldItems[] = UnsoldItemCardDTO::fromArray($row);

        return $unsoldItems;
    }



    public function addDateSold(int $itemId, DateTimeImmutable $dateSold) : void    
    {
        $query = "UPDATE item SET date_sold = ? WHERE item_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addDateSold query :"  . $this->db->error);

        $dateSoldString = $dateSold->format('Y-m-d H:i:s');
        $statement->bind_param('si', $dateSoldString, $itemId);

        if (!$statement->execute())
            throw new Exception("Failed to add date sold to item : " . $statement->error);

        $statement->close();
    }






    // FIX : make it return with title, photo, current bid price, and auction date only
    public function getItemsBySellerId(int $sellerId) : array
    {
        $query = "SELECT * FROM item WHERE seller_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the getItemsBySellerId query : " . $this->db->error);

        $statement->bind_param('i', $sellerId);

        if (!$statement->execute())
            throw new Exception("Failed to getItemsBySellerId : " . $statement->error);


        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $items = [];
        foreach ($rows as $row)
            $items[] = Item::fromArray($row);

        return $items;
    }


    // FIX : make it return with title, photo current bid price, and auction date only
    public function search(SearchItemsRequestDTO $criteria) : array
    {
        $sql = "SELECT item.* 
                FROM item 
                JOIN user ON item.seller_id = user.user_id 
                WHERE 1=1";
        $params = [];
        $types = "";


        $this->applyKeywordFilter($sql, $params, $types, $criteria);
        $this->applyCategoryFilter($sql, $params, $types, $criteria);
        $this->applyStatusFilter($sql, $params, $types, $criteria);
        $this->applyPriceFilter($sql, $params, $types, $criteria);
        $this->sort($sql, $criteria);

        return $this->executeQuery($sql, $params, $types);
    }




    public function updateItemStatus(int $id, string $newStatus) : void
    {
        $query = "UPDATE item SET item_status = ? WHERE item_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the updateItemStatus query :"  . $this->db->error);

        $statement->bind_param('si', $newStatus, $id);

        if (!$statement->execute())
            throw new Exception("Failed to update item status : " . $statement->error);

        $statement->close();
    }



    public function addMeetupCode(int $id, string $meetupCode) : void
    {
        $query = "UPDATE item SET meetup_code = ? WHERE item_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addMeetupCode query : " . $this->db->error);

        $statement->bind_param('si', $meetupCode, $id);

        if (!$statement->execute())
            throw new Exception("Failed to addMeetupCode : " . $statement->error);

        $statement->close();
    }










    private function executeQuery(string $sql, array $params, string $types) : array
    {
        $items = [];
        $statement = $this->db->prepare($sql);

        if (!$statement)
            throw new Exception("Failed to prepare the search query " . $this->db->error);

        if (!empty($params))
            $statement->bind_param($types, ...$params);

        if (!$statement->execute())
            throw new Exception("Failed to do execute the search " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        foreach($rows as $row)
            $items[] = Item::fromArray($row);

        return $items;
    }


    private function applyKeywordFilter(string &$sql, array &$params, string &$types, SearchItemsRequestDTO $criteria)
    {
        if (empty($criteria->searchWord)) return;

        $sql .= " AND (item.title LIKE ? OR item.description LIKE ?)";
        $term = "%" . $criteria->searchWord . "%";
        $params[] = $term;
        $params[] = $term;
        $types .= "ss";
    }

    private function applyCategoryFilter(string &$sql, array &$params, string &$types, SearchItemsRequestDTO $criteria)
    {
        if (empty($criteria->category)) return;

        $placeholders = implode(',', array_fill(0, count($criteria->category), '?'));
        $sql .= " AND item.category IN ($placeholders)";
        foreach ($criteria->category as $cat) {
            $params[] = $cat; 
            $types .= "s";
        }
    }


    private function applyStatusFilter(string &$sql, array &$params, string &$types, SearchItemsRequestDTO $criteria)
    {
        if (empty($criteria->statuses)) return;

        $placeholders = implode(',', array_fill(0, count($criteria->statuses), '?'));
        $sql .= " AND item.item_status IN ($placeholders)";
        
        foreach ($criteria->statuses as $status) {
            $params[] = $status;
            $types .= "s";
        }
    }


    private function applyPriceFilter(string &$sql, array &$params, string &$types, SearchItemsRequestDTO $criteria)
    {
        if ($criteria->minPrice !== null) {
            $sql .= " AND item.current_bid >= ?";
            $params[] = $criteria->minPrice;
            $types .= "d";
        }

        if ($criteria->maxPrice !== null) {
            $sql .= " AND item.current_bid <= ?";
            $params[] = $criteria->maxPrice;
            $types .= "d";
        }
    }


    private function sort(string &$sql, SearchItemsRequestDTO $criteria)
    {
        $order = match($criteria->sortBy) {
            "newest" => " ORDER BY item.created_at DESC",
            "created_asc" => " ORDER BY item.created_at ASC",
            "bid_asc" => " ORDER BY item.current_bid ASC",
            "bid_desc" => " ORDER BY item.current_bid DESC",
            "end_asc" => " ORDER BY item.auction_end ASC",
            "end_desc" => " ORDER BY item.auction_end DESC",
            "rating_desc" => " ORDER BY user.average_rating DESC",
            "rating_asc" => " ORDER BY user.average_rating ASC",
            default => " ORDER BY item.created_at DESC"
        };

        $sql .= $order;
    }



    private function retrieveGetSoldItemsByUserIdQuery() : string 
    {
        return "SELECT 
                    i.item_id, 
                    i.title, 
                    (SELECT image_url FROM item_image WHERE item_id = i.item_id LIMIT 1) as image_url,
                    i.current_bid, 
                    u.fname as buyer_fname, 
                    u.lname as buyer_lname, 
                    i.auction_end as date_sold
                FROM item i
                JOIN bid b ON i.item_id = b.item_id AND b.bid_amount = i.current_bid
                JOIN user u ON b.bidder_id = u.user_id
                WHERE 
                    i.seller_id = ?     
                    AND i.item_status = 'Sold'
                ORDER BY 
                    i.auction_end DESC; ";
    }


    private function retrieveGetActiveItemsByUserId() : string 
    {
        return "
            SELECT 
                i.item_id, i.title, i.item_status,
                i.starting_bid, i.current_bid,
                i.auction_start, i.auction_end,
                (SELECT COUNT(*) FROM bid WHERE item_id = i.item_id) as bid_count,
                (SELECT image_url FROM item_image WHERE item_id = i.item_id LIMIT 1) as image_url
            FROM item i
            WHERE i.seller_id = ? 
            AND i.item_status IN ('Active', 'Pending')
            ORDER BY i.auction_end ASC
        ";
    }



    private function retrieveGetUnsoldItemsByUserIdQuery() : string 
    {
        return "SELECT 
            i.item_id,
            i.title,
            i.starting_bid,
            i.current_bid, -- Useful for 'Cancelled' items to show how high it got
            i.auction_end,
            i.item_status,

            -- 1. THUMBNAIL: Get exactly one image
            (
                SELECT image_url 
                FROM item_image 
                WHERE item_id = i.item_id 
                LIMIT 1
            ) AS image_url,

            -- 2. INTEREST: Count the bids (Even if 0)
            (
                SELECT COUNT(*) 
                FROM bid 
                WHERE item_id = i.item_id
            ) AS bid_count,

            -- 3. REASON: If removed, try to find the Report Reason
            -- This looks for the most recent 'Resolved' report against this item
            (
                SELECT reason_type 
                FROM report 
                WHERE target_item_id = i.item_id 
                AND report_status = 'Resolved' 
                ORDER BY created_at DESC 
                LIMIT 1
            ) AS removal_reason

        FROM item i
        WHERE 
            i.seller_id = ? 
            AND i.item_status IN ('Expired', 'Cancelled By Seller', 'Removed By Admin')
        ORDER BY 
            i.auction_end DESC;";
    }
    


    private function retrieveGetToHandoverItemsByUserId() : string 
    {
        return "SELECT 
                    i.item_id,
                    i.title,
                    i.current_bid,  -- This represents the Final Price
                    u.fname,        -- Buyer's First Name
                    u.lname,        -- Buyer's Last Name
                    (
                        SELECT image_url 
                        FROM item_image 
                        WHERE item_id = i.item_id 
                        LIMIT 1
                    ) AS image_url
                FROM 
                    item i
                JOIN 
                    user u ON i.buyer_id = u.user_id
                WHERE 
                    i.seller_id = ?
                    AND i.item_status = 'Awaiting Meetup'
                ORDER BY 
                    i.auction_end DESC";
    }


}

