<?php

namespace App\Controller\User;

use App\Repository\Formation\FoFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-membre/formations', name: 'user_formations_')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(FoFormationRepository $foFormationRepository): Response
    {
        return $this->render('user/pages/formations/index.html.twig', [
            'data' => $foFormationRepository->findBy(['isOnline' => true], ['startAt' => 'ASC'])
        ]);
    }
}
