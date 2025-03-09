<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $book): void
    {
        $this->_em->persist($book);
        $this->_em->flush();
    }

    public function remove(Book $book): void
    {
        $this->_em->remove($book);
        $this->_em->flush();
    }
}