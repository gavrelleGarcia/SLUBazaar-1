<?php

declare(strict_types=1);

require_once '../model/Item.php';
require_once '../dto/SearchItemFilter.php';

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

        $statement->close();
    }


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



    public function search(SearchItemFilter $criteria) : array
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


    private function applyKeywordFilter(string &$sql, array &$params, string &$types, SearchItemFilter $criteria)
    {
        if (empty($criteria->searchWord)) return;

        $sql .= " AND (item.title LIKE ? OR item.description LIKE ?)";
        $term = "%" . $criteria->searchWord . "%";
        $params[] = $term;
        $params[] = $term;
        $types .= "ss";
    }

    private function applyCategoryFilter(string &$sql, array &$params, string &$types, SearchItemFilter $criteria)
    {
        if (empty($criteria->category)) return;

        $placeholders = implode(',', array_fill(0, count($criteria->category), '?'));
        $sql .= " AND item.category IN ($placeholders)";
        foreach ($criteria->category as $cat) {
            $params[] = $cat; 
            $types .= "s";
        }
    }


    private function applyStatusFilter(string &$sql, array &$params, string &$types, SearchItemFilter $criteria)
    {
        if (empty($criteria->statuses)) return;

        $placeholders = implode(',', array_fill(0, count($criteria->statuses), '?'));
        $sql .= " AND item.item_status IN ($placeholders)";
        
        foreach ($criteria->statuses as $status) {
            $params[] = $status;
            $types .= "s";
        }
    }


    private function applyPriceFilter(string &$sql, array &$params, string &$types, SearchItemFilter $criteria)
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


    private function sort(string &$sql, SearchItemFilter $criteria)
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



}

