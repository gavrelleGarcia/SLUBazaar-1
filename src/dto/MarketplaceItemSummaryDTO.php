<?php
declare(strict_types=1);

// The one who will use this will be the SERVICE not the REPOSITORY cause there is logic to check to first
// This will be also used in the UserProfile Active Listings, the $isCliclable datafield won't be used tho
class MarketplaceItemSummaryDTO implements JsonSerializable
{
    public function __construct(
        public int $itemId,
        public string $title,
        public string $imageUrl,
        public string $status, // either 'Pending', 'Active'
        
        // UI READY FIELDS (Computed)
        public float $displayPrice,       // The actual number to show
        public string $priceLabel,        // "Starting Bid" or "Current Bid"
        
        public string $timerTargetIso,    // The exact date for the JS timer
        public string $timerLabel,        // "Starts in:" or "Ends in:"

        // But if this item is used in the profile section, this variable
        // $isClickable won't be used I think, since the seller is the owner
        public bool $isClickable          // Can the user bid right now? 
    ) {}

    public function jsonSerialize(): array {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'image' => $this->imageUrl,
            'status' => $this->status,
            'price' => [
                'amount' => $this->displayPrice,
                'label' => $this->priceLabel
            ],
            'timer' => [
                'target' => $this->timerTargetIso,
                'label' => $this->timerLabel
            ],
            'is_clickable' => $this->isClickable
        ];
    }
}