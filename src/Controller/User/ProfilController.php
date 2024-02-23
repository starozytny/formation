<?php

namespace App\Controller\User;

use App\Entity\Formation\FoFormation;
use App\Entity\Main\User;
use App\Repository\Formation\FoFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-membre/mon-compte', name: 'user_profil_')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/pages/profil/index.html.twig', [
            'elem' => $user
        ]);
    }
}
