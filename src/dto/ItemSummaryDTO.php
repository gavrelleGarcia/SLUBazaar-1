<?php


declare(strict_types=1);

// This class is only used for the communication of ItemRepository and A service
// It will never go to the frontend side
class ItemSummaryDTO 
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $status, // Either 'Pending' or 'Active' or 'Sold'
        public readonly float $startingBid, 
        public readonly float $currentBid,
        public readonly string $auctionStart,
        public readonly string $auctionEnd,
        public readonly int $bidCount,      
        public readonly string $imageUrl   
    ) {}

    public static function fromArray(array $data) : self
    {
        return new self(
            (int)$data['item_id'], 
            $data['title'],
            $data['item_status'], 
            (float)$data['starting_bid'], 
            (float)$data['currentBid'], 
            $data['auction_start'], 
            $data['auction_end'], 
            (int)$data['bid_count'], 
            $data['image_url']
        );
    }

}