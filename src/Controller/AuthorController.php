<?php

namespace App\Controller;

use App\Service\AuthorService;
use App\DTO\AuthorDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    #[Route('/author', name: 'app_author', methods: ['POST'])]
    public function createAuthor(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $authorDTO = new AuthorDTO($data['firstName'],$data['lastName']);

        $author = $this->authorService->createAuthor($authorDTO);
        
        return new JsonResponse(
            [
                'id' => $author->getId(), 
                'firstName' => $author->getfirstName(),
                'lastName' => $author->getLastName()
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    #[Route('/author/{id}', name: 'delete_author', methods: ['DELETE'])]
    public function deleteAuthor(int $id): JsonResponse
    {
        $this->authorService->deleteAuthor($id);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}


