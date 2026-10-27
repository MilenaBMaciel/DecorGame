<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_menu_')]
final class MenuController extends AbstractController
{
    #[Route('/menu', name: 'main')]
    public function index(): Response
    {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    #[Route('/menu/edit', name: 'edit')]
    public function editMenu(): Response
    {
        return $this->render('menu/edit.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    // Tela inicial: quem já está logado vai direto para o menu do jogo
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
