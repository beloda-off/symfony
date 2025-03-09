<?php

namespace App\Service;

use App\Repository\AuthorRepository;
use App\DTO\AuthorDTO;
use App\Entity\Author;

class AuthorService
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function createAuthor(AuthorDTO $authorDTO): Author
    {
        $author = new Author();
        $author->setFirstName($authorDTO->firstName);
        $author->setLastName($authorDTO->lastName);

        $this->authorRepository->save($author);

        return $author;
    }

    public function deleteAuthor(int $id): void
    {
        $author = $this->authorRepository->find($id);
        if ($author) {
            $this->authorRepository->remove($author);
        }
    }
}
