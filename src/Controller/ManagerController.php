<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-manager', name: 'manager_')]
class ManagerController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('manager/pages/index.html.twig');
    }

    #[Route('/parametres/modifier', name: 'settings_update', options: ['expose' => true])]
    public function settings(Request $request): Response
    {
        return $this->render('manager/pages/settings/update.html.twig', ['highlight' => $request->query->get('h')]);
    }
}
