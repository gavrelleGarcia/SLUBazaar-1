<?php


declare(strict_types=1);

class ConversationDTO implements JsonSerializable
{
    

    public function __construct(
        public readonly int $conversationId,
        public readonly string $status,
        public readonly string $itemTitle,
        public readonly string $otherUserFname,
        public readonly string $otherUserLname,
        public readonly string $lastMessage,
        public readonly DateTimeImmutable $lastMessageTime,
        public readonly bool $isRead,
        public readonly bool $isSeller
    ) {}


    public function jsonSerialize(): array
    {
        return [
            'conversationId' => $this->conversationId,
            'status'         => $this->status,
            'itemTitle'      => $this->itemTitle,
            'otherUserFname' => $this->otherUserFname,
            'otherUserLname' => $this->otherUserLname,
            'lastMessage'    => $this->lastMessage,
            'lastMessageTime'=> $this->lastMessageTime->format('c'), 
            'isRead'         => $this->isRead,
            'isSeller'       => $this->isSeller
        ];
    }

    public static function fromArray(array $data): self 
    {
        return new self(
            (int) $data['conversation_id'],
            $data['status'],
            $data['item_title'],
            $data['other_fname'],
            $data['other_lname'],
            $data['message_text'],
            new DateTimeImmutable($data['created_at']),
            (bool) $data['is_read'],
            (bool) $data['is_seller']
        );
    }
}