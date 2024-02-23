<?php

namespace App\Controller\User;

use App\Entity\Formation\FoFormation;
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
    #[Route('/formation/{slug}', name: 'read')]
    public function read(FoFormation $obj): Response
    {
        return $this->render('user/pages/formations/read.html.twig', [
            'elem' => $obj
        ]);
    }
}
