<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/room')]
final class AdminRoomController extends AbstractController
{
    #[Route(name: 'app_admin_room_index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('admin_room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $room->setImage($fileUploader->upload($form->get('imageFile')->getData(), 'rooms'));
            // Sem slots aqui: cada usuário ganha os seus ao abrir a sala (SlotManager)
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_room/new.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_room_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Room $room, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(RoomType::class, $room, ['image_required' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Só troca a imagem se uma nova foi enviada, e apaga a antiga
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $oldImage = $room->getImage();
                $room->setImage($fileUploader->upload($imageFile, 'rooms'));
                $fileUploader->remove('rooms', $oldImage);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_room_delete', methods: ['POST'])]
    public function delete(Request $request, Room $room, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->getPayload()->getString('_token'))) {
            
            foreach ($room->getSlots() as $slot) {
                $entityManager->remove($slot);
            }

            $fileUploader->remove('rooms', $room->getImage());
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_room_index', [], Response::HTTP_SEE_OTHER);
    }
}
