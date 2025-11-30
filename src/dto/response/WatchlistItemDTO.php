<?php
declare(strict_types=1);

/**
 * The class that will be passed to Profile View (Watchlist)
 */
class WatchlistItemDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $watchlistId,
        public readonly int $itemId,
        public readonly string $title,
        public readonly float $currentBid,
        public readonly DateTimeImmutable $auctionEnd,
        public readonly string $images, 
        public readonly DateTimeImmutable $addedAt
    ) {}



    public function jsonSerialize(): array
    {
        return [
            'watchlist_id' => $this->watchlistId,
            'item_id'      => $this->itemId,
            'title'        => $this->title,
            'current_bid'  => $this->currentBid,
            'auction_end'  => $this->auctionEnd->format('c'),
            'images'       => $this->images, 
            'added_at'     => $this->addedAt->format('c')
        ];
    }

    
    public static function fromArray(array $data): self
    {
        return new self(
            (int)$data['watchlist_id'],
            (int)$data['item_id'],
            $data['title'],
            (float)$data['current_bid'],
            (new DateTimeImmutable($data['auction_end'])),
            $data['image_url'], 
            (new DateTimeImmutable($data['added_at']))
        );
    }
}