<?php
declare(strict_types=1);

class RatingDetailsDTO implements JsonSerializable
{
    public function __construct(
        public int $ratingValue,
        public ?string $comment,
        public string $date,

        // Item Details
        public int $itemId,
        public string $itemTitle,
        public float $itemPrice, // The final sold price

        // The "Other Person": Rater who rate you or Ratee who did you rate 
        public string $otherUserName
    ) {}

    
    public function jsonSerialize(): array
    {
        return [
            'rating' => $this->ratingValue,
            'comment' => $this->comment,
            'date' => $this->date,
            'item' => [
                'id' => $this->itemId,
                'title' => $this->itemTitle,
                'price' => $this->itemPrice
            ],
            'user' => $this->otherUserName
        ];
    }


    public static function fromArray(array $data) : self
    {
        return new RatingDetailsDTO(
                (int)$data['rating_value'],
                $data['comment'] ?? null,
                (new DateTimeImmutable($data['created_at']))->format('M d, Y'),
                (int)$data['item_id'],
                $data['title'],
                (float)$data['final_price'],
                $data['fname'] . ' ' . $data['lname'] 
        );
    }
}