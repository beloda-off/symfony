<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Repository\PublisherRepository;
use App\DTO\BookDTO;
use App\DTO\BookListDTO;
use App\Entity\Book;

use Exception;

class BookService
{
    private BookRepository $bookRepository;
    private AuthorRepository $authorRepository;
    private PublisherRepository $publisherRepository;

    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository, PublisherRepository $publisherRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->publisherRepository = $publisherRepository;
    }

    public function createBook(BookDTO $bookDTO): Book
    {
        $authors = $this->authorRepository->findAuthors($bookDTO->authorIds);
        $publisher = $this->publisherRepository->findPublisher($bookDTO->publisher); 

        if (count($authors) !== count($bookDTO->authorIds)) {
            throw new Exception('One or more authors not found');
        }

        if (!$publisher) {
            throw new Exception('Publisher not found');
        }
    
        // Создаем новую книгу и устанавливаем её свойства
        $book = new Book();
        $book->setTitle($bookDTO->title);
        $book->setYear($bookDTO->year);
        $book->setPublisher($publisher); 
    
        // Добавляем авторов к книге
        foreach ($authors as $author) {
            $book->addAutor($author);
        }
    
        // Сохраняем книгу в репозитории
        $this->bookRepository->save($book);
        return $book;
    }

    public function deleteBook(int $id): void
    {
        
        $book = $this->bookRepository->find($id);
        if (!$book) {
            throw new Exception('Book not found');
        }

        $this->bookRepository->remove($book);
    }

    public function getAllBooks(): array
    {
        $books = $this->bookRepository->findAllBooksWithDetails();
    
        $bookDTOs = [];
    
        foreach ($books as $book) {
            // Получаем список всех авторов книги
            $authors = $book->getAuthor(); 
            $authorLastNames = [];
    
            // Проходим по каждому автору и получаем фамилии
            foreach ($authors as $author) {
                $authorLastNames[] = $author->getLastName(); // Получаем фамилии
            }
    
            // Добавляем DTO для книги
            $bookDTOs[] = new BookListDTO(
                $book->getTitle(),
                $authorLastNames, // Передаем массив фамилий
                $book->getPublisher()->getName()
                

            );
        }
    
        return $bookDTOs;
    }
}