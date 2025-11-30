<?php
declare(strict_types=1);


/**
 * The class that will be passed to Marketplace View (Active Items) and
 * Also to Profile View (My Active Items View)
 */

// The one who will use this will be the SERVICE not the REPOSITORY cause there is logic to check to first
// This will be also used in the UserProfile Active Listings, the $isCliclable datafield won't be used tho
class ItemCardDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly string $status, // either 'Pending', 'Active'
        public readonly int $bidCount,
        
        // UI READY FIELDS (Computed)
        public readonly float $displayPrice,       // The actual number to show
        public readonly string $priceLabel,        // "Starting Bid" or "Current Bid"
        
        public readonly DateTimeImmutable $timerTargetIso,    // The exact date for the JS timer
        public readonly string $timerLabel,        // "Starts in:" or "Ends in:"
        public readonly bool $canBid          // Can the user bid right now? If he is the seller or buyer 
    ) {}



    public function jsonSerialize(): array {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'image' => $this->imageUrl,
            'status' => $this->status,
            'bidCount' => $this->bidCount,
            'price' => [
                'amount' => $this->displayPrice,
                'label' => $this->priceLabel
            ],
            'timer' => [
                'target' => $this->timerTargetIso->format('c'),
                'label' => $this->timerLabel
            ],
            'canBid' => $this->canBid
        ];
    }

}