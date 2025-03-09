<?php

namespace App\DTO;

class BookDTO
{
    public string $title;
    public array $authorIds;
    public int $year;
    public int $publisher;

    public function __construct(string $title, array $authorIds, int $year, int $publisher)
    {
        $this->title = $title;
        $this->authorIds = $authorIds;
        $this->year = $year;
        $this->publisher = $publisher;
    }
}