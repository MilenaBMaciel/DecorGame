<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RoomRepository;
use App\Entity\Room;
use App\Entity\Slot;
use App\Form\SlotType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/room', name: 'app_')]
final class RoomController extends AbstractController
{
    #[Route('s', name: 'rooms')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms= $roomRepository->findAll(); 
        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/{id}', name: 'room_get')]
    public function getRoom(Room $room): Response
    {
        return $this->render('room/room.html.twig', [
            'room' => $room,
        ]);
    }


    #[Route('/slot/{id}/edit', name: 'slot_edit', methods: ['GET', 'POST'])]
    public function editSlot(Slot $slot, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_room_get', ['id' => $slot->getRoom()->getId()]);
        }

        return $this->render('room/edit_slot.html.twig', [
            'slot' => $slot,
            'form' => $form,
        ]);
    }
}
