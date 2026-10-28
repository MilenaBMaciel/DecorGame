<?php

namespace App\Controller\API;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// API: devolve as salas em JSON (só os campos do grupo "api_report"). Só para admins.
#[Route('/api/rooms', name: 'api_rooms_')]
#[IsGranted('ROLE_ADMIN', message: 'Only admins can access the API.')]
final class RoomController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): JsonResponse
    {
        return $this->json($roomRepository->findAllOrdered(), Response::HTTP_OK, [], [
            'groups' => 'api_report',
        ]);
    }
}
