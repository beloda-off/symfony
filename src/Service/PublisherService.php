<?php
namespace App\Service;

use App\DTO\PublisherDTO;
use App\Entity\Publisher;
use App\Repository\PublisherRepository;

class PublisherService
{
    private PublisherRepository $publisherRepository;
    public function __construct(PublisherRepository $publisherRepository)
    {
        $this->publisherRepository = $publisherRepository;
    }

    public function updatePublisher(int $id, PublisherDTO $publisherDTO): ?Publisher
    {
        return $this->publisherRepository->updateFieldsById($id, $publisherDTO->name, $publisherDTO->address);
    }

    public function deletePublisher(int $id): void
    {
        
        $publisherId = $this->publisherRepository->find($id);
        if (!$publisherId) {
            throw new Exception('Publisher not found');
        }

        $this->publisherRepository->removePublisherWithBooks($id);
    }

    
}