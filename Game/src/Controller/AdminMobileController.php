<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Form\MobileType;
use App\Repository\MobileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mobile')]
final class AdminMobileController extends AbstractController
{
    #[Route(name: 'app_admin_mobile_index', methods: ['GET'])]
    public function index(MobileRepository $mobileRepository): Response
    {
        return $this->render('admin_mobile/index.html.twig', [
            'mobiles' => $mobileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_mobile_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mobile = new Mobile();
        $form = $this->createForm(MobileType::class, $mobile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mobile);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_mobile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mobile/new.html.twig', [
            'mobile' => $mobile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_mobile_show', methods: ['GET'])]
    public function show(Mobile $mobile): Response
    {
        return $this->render('admin_mobile/show.html.twig', [
            'mobile' => $mobile,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_mobile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mobile $mobile, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MobileType::class, $mobile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_mobile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mobile/edit.html.twig', [
            'mobile' => $mobile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_mobile_delete', methods: ['POST'])]
    public function delete(Request $request, Mobile $mobile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mobile->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mobile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_mobile_index', [], Response::HTTP_SEE_OTHER);
    }
}
