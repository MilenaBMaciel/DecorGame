<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RoomRepository;

#[Route('/room', name: 'app_')]
final class RoomController extends AbstractController
{
    #[Route('s', name: 'room')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms= $roomRepository->findAll(); 
        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }
}
