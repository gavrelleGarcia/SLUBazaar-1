<?php
// src/Service/AuctionService.php

declare(strict_types=1);

class AuctionService 
{
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }






























    

    /**
     * Checks for expired active items and closes them.
     * Returns the number of items processed.
     */
    public function processExpiredAuctions(): int 
    {
        $count = 0;
        
        // 1. START TRANSACTION
        // We do this to ensure data integrity.
        $this->conn->begin_transaction();

        try {
            // 2. FIND CANDIDATES
            // We select items that are Active AND past their end time.
            // "FOR UPDATE" locks these rows so no one else can modify them while we work.
            $sql = "SELECT item_id, title, seller_id 
                    FROM item 
                    WHERE item_status = 'Active' 
                    AND auction_end <= NOW() 
                    LIMIT 50 
                    FOR UPDATE"; 

            $result = $this->conn->query($sql);

            if ($result && $result->num_rows > 0) {
                // Fetch all rows first to keep the code clean
                $items = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($items as $item) {
                    $this->finalizeSingleItem($item);
                    $count++;
                }
            }

            // 3. SAVE CHANGES
            $this->conn->commit();
            return $count;

        } catch (Exception $e) {
            // If anything goes wrong, undo everything.
            $this->conn->rollback();
            // In production, you would log this error to a file
            echo "Error processing auctions: " . $e->getMessage() . "\n";
            return 0;
        }
    }

    /**
     * Helper to close a specific item
     */
    private function finalizeSingleItem(array $item): void 
    {
        $itemId = (int)$item['item_id'];
        $sellerId = (int)$item['seller_id'];
        $title = $item['title'];

        // A. FIND HIGHEST BIDDER
        // Get the top 1 bid
        $sqlBid = "SELECT bidder_id, bid_amount 
                   FROM bid 
                   WHERE item_id = ? 
                   ORDER BY bid_amount DESC 
                   LIMIT 1";
        
        $stmtBid = $this->conn->prepare($sqlBid);
        $stmtBid->bind_param("i", $itemId);
        $stmtBid->execute();
        $resBid = $stmtBid->get_result();
        $winner = $resBid->fetch_assoc();
        $stmtBid->close();

        if ($winner) {
            // --- SCENARIO 1: ITEM SOLD ---
            $buyerId = (int)$winner['bidder_id'];
            $finalPrice = (float)$winner['bid_amount'];
            
            // Generate 6-digit verification code
            $code = str_pad((string)rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // 1. Update Item Table
            $updateSql = "UPDATE item 
                          SET item_status = 'Awaiting Meetup', 
                              buyer_id = ?, 
                              current_bid = ?, 
                              meetup_code = ?,
                              date_sold = NOW()
                          WHERE item_id = ?";
            $stmtUpd = $this->conn->prepare($updateSql);
            $stmtUpd->bind_param("idsi", $buyerId, $finalPrice, $code, $itemId);
            $stmtUpd->execute();
            $stmtUpd->close();

            // 2. Create Chat Channel (Requirement A.a.2.13)
            // Check if chat exists first to be safe, or just insert
            $chatSql = "INSERT INTO conversation (item_id, buyer_id, seller_id, status) VALUES (?, ?, ?, 'Active')";
            $stmtChat = $this->conn->prepare($chatSql);
            $stmtChat->bind_param("iii", $itemId, $buyerId, $sellerId);
            $stmtChat->execute();
            $stmtChat->close();

            // 3. Notify Seller
            $this->sendNotification($sellerId, "Item Sold", "Your item '$title' has been sold for ₱$finalPrice!");
            
            // 4. Notify Buyer
            $this->sendNotification($buyerId, "Auction Won", "You won '$title'! Check your 'To Handover' tab.");

        } else {
            // --- SCENARIO 2: NO BIDS (EXPIRED) ---
            $updateSql = "UPDATE item SET item_status = 'Expired' WHERE item_id = ?";
            $stmtUpd = $this->conn->prepare($updateSql);
            $stmtUpd->bind_param("i", $itemId);
            $stmtUpd->execute();
            $stmtUpd->close();

            // Notify Seller
            $this->sendNotification($sellerId, "Auction Expired", "No bids were placed on '$title'. You can relist it.");
        }
    }

    private function sendNotification(int $userId, string $title, string $content): void
    {
        $sql = "INSERT INTO notification (user_id, notif_title, content, notif_type) VALUES (?, ?, ?, 'System')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $title, $content);
        $stmt->execute();
        $stmt->close();
    }
}