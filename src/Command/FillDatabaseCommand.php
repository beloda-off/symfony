<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class FillDatabaseCommand extends Command
{
    protected static $defaultName = 'fill-database';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Fills the database with test data: authors, books, and publishers.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Создание нескольких издательств

        $arrPublisher = [];
        for ($i = 1; $i <= 5; $i++) {

            $publisher = new Publisher();
            $publisherName = 'Издатель №' . $i;
            $arrPublisher[] =  $publisher;
            $publisher->setName($publisherName);
            $publisher->setAddress('СПБ ' . $i . '-ая Красноармейская ул., дом ' .rand(1,30));
            $this->entityManager->persist($publisher);
        }

        // Создание нескольких авторов и их книг
        for ($i = 1; $i <= 10; $i++) {
            $author = new Author();
            $author->setFirstName('Имя ' . $i);
            $author->setLastName('Фамилия ' . $i);
            $this->entityManager->persist($author);
            
            // намерянно создаю авторов без книг
            if($i % 2 == 0){
                continue;
            }

            //книги
            for ($j = 1; $j <= rand(2,5); $j++) {
                $book = new Book();
                $book->setTitle('Книга ' . ($i * 2 + $j - 1));
                $book->addAutor($author);
                $book->setYear(2000 + $i);
                $randomPublisher = $arrPublisher[array_rand($arrPublisher)];
                $book->setPublisher($randomPublisher);
                $this->entityManager->persist($book);
            }
        }

        // Сохранение изменений в базе данных
        $this->entityManager->flush();

        $output->writeln('БД заполнена тестовыми данными!');

        return Command::SUCCESS;
    }
}