<?php

namespace App\Controller;

use App\Service\BookService;
use App\DTO\BookDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    #[Route('/book', name: 'create_book', methods: ['POST'])]
    public function createBook(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        //var_dump($data);exit('55');
        $bookDTO = new BookDTO($data['title'], $data['authorIds'],  $data['publisher'], $data['year']);
        //dd($bookDTO);
        $book = $this->bookService->createBook($bookDTO);
        //dd($book);
        return new JsonResponse(
            [
            'id' => $book->getId(),
            'title' => $book->getTitle(), 
            'year' => $book->getYear(),
            'publisher' => $book->getPublisher()->getId(),
            ],
            JsonResponse::HTTP_CREATED);
    }

    #[Route('/book/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function deleteBook(int $id): JsonResponse
    {
        $this->bookService->deleteBook($id);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/book', name: 'get_book', methods: ['GET'])]
    public function getBooks(): JsonResponse
    {
        $bookDTOs = $this->bookService->getAllBooks();
        
        $result = [];
        foreach ($bookDTOs as $bookDTO) {
            $result[] = [
                'title' => $bookDTO->getTitle(),
                'authorLastName' => $bookDTO->getAuthorLastName(),
                'publisherName' => $bookDTO->getPublisherName(),
            ];
        }

        return new JsonResponse($result);
    }
}