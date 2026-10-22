<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/menu', name: 'app_menu_')]
final class MenuController extends AbstractController
{
    #[Route(name: 'main')]
    public function index(): Response
    {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function editMenu(): Response
    {
        return $this->render('menu/edit.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }
}
