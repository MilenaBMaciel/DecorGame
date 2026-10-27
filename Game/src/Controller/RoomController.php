<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RoomRepository;
use App\Entity\Room;
use App\Entity\Slot;
use App\Entity\User;
use App\Form\SlotType;
use App\Service\SlotManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Cada usuário decora as salas do seu jogo; por isso é preciso estar logado
#[Route(name: 'app_')]
#[IsGranted('ROLE_USER')]
final class RoomController extends AbstractController
{
    #[Route('/rooms', name: 'rooms', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/room/{id}', name: 'room_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getRoom(Room $room, #[CurrentUser] User $user, SlotManager $slotManager): Response
    {
        return $this->render('room/room.html.twig', [
            'room' => $room,
            'slots' => $slotManager->getSlotsFor($room, $user),
        ]);
    }

    #[Route('/room/slot/{id}/edit', name: 'slot_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editSlot(Slot $slot, #[CurrentUser] User $user, Request $request, EntityManagerInterface $em): Response
    {
        // Ninguém mexe na decoração dos outros
        if ($slot->getUser() !== $user) {
            throw $this->createAccessDeniedException('Este slot pertence a outro usuário.');
        }

        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_room_get', ['id' => $slot->getRoom()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/edit_slot.html.twig', [
            'slot' => $slot,
            'form' => $form,
        ]);
    }
}
