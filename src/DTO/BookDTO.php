<?php

namespace App\DTO;

class BookDTO
{
    public string $title;
    public array $authorIds;
    public int $year;
    public int $publisher;
    private string $authorLastName;
    private string $publisherName;

    public function __construct(string $title, array $authorIds,  int $publisher, int $year)
    {
        $this->title = $title;
        $this->authorIds = $authorIds;
        $this->year = $year??'';
        $this->publisher = $publisher;
        
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    
}