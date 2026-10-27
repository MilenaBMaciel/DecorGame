<?php

namespace App\Controller\API;

use App\Repository\FurnitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// API: devolve os móveis em JSON (só os campos do grupo "api_report")
#[Route('/api/furniture', name: 'api_furniture_')]
final class FurnitureController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(FurnitureRepository $furnitureRepository): JsonResponse
    {
        return $this->json($furnitureRepository->findAll(), Response::HTTP_OK, [], [
            'groups' => 'api_report',
        ]);
    }
}
