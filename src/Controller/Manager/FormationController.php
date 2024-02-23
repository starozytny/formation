<?php

namespace App\Controller\Manager;

use App\Entity\Formation\FoNews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/manager/formations', name: 'manager_formations_')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'index', options: ['expose' => true])]
    public function index(Request $request): Response
    {
        return $this->render('manager/pages/formations/index.html.twig', ['highlight' => $request->query->get('h')]);
    }

    #[Route('/formation/ajouter', name: 'create')]
    public function create(): Response
    {
        return $this->render('manager/pages/formations/create.html.twig');
    }

    #[Route('/formation/modifier/{id}', name: 'update', options: ['expose' => true])]
    public function update(FoNews $elem, SerializerInterface $serializer): Response
    {
        $obj  = $serializer->serialize($elem, 'json', ['groups' => FoNews::FORM]);
        return $this->render('manager/pages/formations/update.html.twig', ['elem' => $elem, 'obj' => $obj]);
    }
}
