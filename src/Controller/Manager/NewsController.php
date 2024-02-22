<?php

namespace App\Controller\Manager;

use App\Entity\Formation\FoNews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/manager/actualites', name: 'manager_news_')]
class NewsController extends AbstractController
{
    #[Route('/', name: 'index', options: ['expose' => true])]
    public function index(Request $request): Response
    {
        return $this->render('manager/pages/news/index.html.twig', ['highlight' => $request->query->get('h')]);
    }

    #[Route('/actualite/ajouter', name: 'create')]
    public function create(): Response
    {
        return $this->render('manager/pages/news/create.html.twig');
    }

    #[Route('/actualite/modifier/{id}', name: 'update', options: ['expose' => true])]
    public function update(FoNews $elem, SerializerInterface $serializer): Response
    {
        $obj  = $serializer->serialize($elem, 'json', ['groups' => FoNews::FORM]);
        return $this->render('manager/pages/news/update.html.twig', ['elem' => $elem, 'obj' => $obj]);
    }
}
