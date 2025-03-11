<?php

namespace App\Command;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveAuthorsWithoutBooksCommand extends Command
{
    protected static $defaultName = 'removeAuthorsWithoutBooks';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Removes all authors who do not have any books.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Получаем всех авторов
        $authors = $this->entityManager->getRepository(Author::class)->findAll();
        $removedCount = 0;

        foreach ($authors as $author) {
            // Проверяем, есть ли у автора книги
            if ($author->getAutor()->isEmpty()) { // Используем метод getAutor для проверки
                $this->entityManager->remove($author);
                $removedCount++;
            }
        }

        // Сохраняем изменения в базе данных
        $this->entityManager->flush();

        if ($removedCount > 0) {
            $output->writeln('удалены авторы у которых нет книг. Количество удаленных: ' . $removedCount);
        } else {
            $output->writeln("Не найдены авторы у которых нет книг");
        }

        return Command::SUCCESS;
    }
}