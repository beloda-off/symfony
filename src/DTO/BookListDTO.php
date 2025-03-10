<?php
namespace App\DTO;

class BookListDTO
{
    private string $title;
    private array $authorLastName;
    private string $publisherName;

    public function __construct(string $title, array $authorLastName, string $publisherName)
    {
        $this->title = $title;
        $this->authorLastName = $authorLastName;
        $this->publisherName = $publisherName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthorLastName(): array
    {
        return $this->authorLastName;
    }

    public function getPublisherName(): string
    {
        return $this->publisherName;
    }
}