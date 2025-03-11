<?php

namespace App\DTO;

class PublisherDTO
{
    public ?string $name = null;    // Может не передаваться
    public ?string $address = null;  // Может не передаваться

    public function __construct(?string $name = null, ?string $address = null)
    {
        $this->name = $name;
        $this->address = $address;
    }
}
