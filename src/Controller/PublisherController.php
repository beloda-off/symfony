<?php

namespace App\Controller;

use App\DTO\PublisherDTO;
use App\Service\PublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PublisherController extends AbstractController
{
    private PublisherService $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    #[Route('/publisher/{id}', methods: ['PATCH'], name: 'publisher_patch')]
    public function editPublisher(int $id, Request $request): JsonResponse
    {
        // Получаем данные из тела запроса
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;     // Имя, если передано
        $address = $data['address'] ?? null; // Адрес, если передан

        // Создаем DTO для обновления
        $publisherDTO = new PublisherDTO($name, $address);

        // Обновляем издателя через сервис
        $updatedPublisher = $this->publisherService->updatePublisher($id, $publisherDTO);

        if (!$updatedPublisher) {
            // Если издатель не найден, возвращаем 404
            return new JsonResponse(['error' => 'Publisher not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Возвращаем обновленного издателя
        return new JsonResponse($updatedPublisher, JsonResponse::HTTP_OK);
    }

    #[Route('/publisher/{id}', name: 'delete_publisher', methods: ['DELETE'])]
    public function deletepublisher(int $id): JsonResponse
    {
        $this->publisherService->deletepublisher($id);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
