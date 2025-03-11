<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;

/**
 * @extends ServiceEntityRepository<Publisher>
 */
class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    public function findPublisher(int $id): ?Publisher
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateFieldsById(int $id, ?string $name, ?string $address): ?Publisher
    {
        $publisher = $this->find($id);

        if ($publisher) {
            if ($name !== null) {
                $publisher->setName($name);
            }
            if ($address !== null) {
                // Предполагается, что в модели Publisher есть метод setAddress()
                $publisher->setAddress($address); 
            }
            
            $this->_em->persist($publisher);
            $this->_em->flush();
        }

        return $publisher;
    }

    
    public function removePublisherWithBooks(int $publisherId): void
    {
        // Получение издателя
        $publisher = $this->findPublisher($publisherId);
        
        if (!$publisher) {
            throw new \Exception("Publisher not found");
        }

        // Получение связанных книг
        $books = $this->_em->getRepository(Book::class)->findBy(['publisher' => $publisher]);

        // Удаление связанных книг
        foreach ($books as $book) {
            $this->_em->remove($book);
        }

        // Удаление издателя
        $this->_em->remove($publisher);
        $this->_em->flush();
    }
    
}


