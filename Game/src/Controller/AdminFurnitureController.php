<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Form\FurnitureType;
use App\Service\FileUploader;
use App\Repository\FurnitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/furniture')]
#[IsGranted('ROLE_ADMIN', message: 'Only admins can edit the game.')]
final class AdminFurnitureController extends AbstractController
{
    #[Route(name: 'app_admin_furniture_index', methods: ['GET'])]
    public function index(FurnitureRepository $furnitureRepository): Response
    {
        return $this->render('admin_furniture/index.html.twig', [
            'furniture_items' => $furnitureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_furniture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $furniture = new Furniture();
        $form = $this->createForm(FurnitureType::class, $furniture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $furniture->setImage($fileUploader->upload($form->get('imageFile')->getData(), 'furniture'));
            $entityManager->persist($furniture);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_furniture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_furniture/new.html.twig', [
            'furniture' => $furniture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_furniture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Furniture $furniture, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(FurnitureType::class, $furniture, ['image_required' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Só troca a imagem se uma nova foi enviada, e apaga a antiga
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $oldImage = $furniture->getImage();
                $furniture->setImage($fileUploader->upload($imageFile, 'furniture'));
                $fileUploader->remove('furniture', $oldImage);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_furniture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_furniture/edit.html.twig', [
            'furniture' => $furniture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_furniture_delete', methods: ['POST'])]
    public function delete(Request $request, Furniture $furniture, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {

        if ($this->isCsrfTokenValid('delete'.$furniture->getId(), $request->getPayload()->getString('_token'))) {
        
            // Esvazia todos os slots que têm este móvel
            foreach ($furniture->getSlots() as $slot) {
                $slot->setFurniture(null);
            }
    
            $fileUploader->remove('furniture', $furniture->getImage());
            $entityManager->remove($furniture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_furniture_index', [], Response::HTTP_SEE_OTHER);
    }
}
