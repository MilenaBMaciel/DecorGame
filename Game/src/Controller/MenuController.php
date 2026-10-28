<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(name: 'app_menu_')]
final class MenuController extends AbstractController
{
    #[Route('/menu', name: 'main')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    #[Route('/menu/edit', name: 'edit')]
    #[IsGranted('ROLE_ADMIN', message: 'Only admins can edit the game.')]
    public function editMenu(): Response
    {
        return $this->render('menu/edit.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    // Tela inicial (sem IsGranted: aberta para todos): quem já está logado vai direto para o menu do jogo
    #[Route('/', name: 'home')]
    public function menu(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_menu_main');
        }

        return $this->render('menu/home.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }
}
