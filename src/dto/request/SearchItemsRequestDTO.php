<?php

declare(strict_types=1);

class SearchItemsRequestDTO
{
    public readonly ?string $searchWord;
    public readonly array $category; // either Electronics, Others, etc 2 or 3 or 4 or all
    public readonly array $statuses; // either pending or active or allshi
    public readonly string $sortBy;
    public readonly ?float $minPrice;
    public readonly ?float $maxPrice;
    // TODO : add a page in the marketplace


    public static function fromArray(array $data) : self
    {
        $criteria = new self();


        $criteria->searchWord = $data['q'] ?? null;
        $criteria->category = $data['categories'] ?? [];
        $criteria->sortBy = $data['sort'] ?? 'newest';

        $rawStatus = $data['statuses']?? 'All';

        if ($rawStatus === 'All')
            $criteria->statuses = ['Active', 'Pending'];
        else 
            $criteria->statuses = [$rawStatus];  
        
        $criteria->minPrice = isset($data['min']) && is_numeric($data['min']) ?
                                (float)$data['min'] : null;
        $criteria->maxPrice = isset($data['max']) && is_numeric($data['max']) ?
                                (float)$data['max'] : null;

        return $criteria;
    }
    

}