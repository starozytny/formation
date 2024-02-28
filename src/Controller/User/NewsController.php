<?php

namespace App\Controller\User;

use App\Entity\Enum\Formation\NewsVisibility;
use App\Entity\Formation\FoNews;
use App\Repository\Formation\FoNewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-membre/actualites', name: 'user_news_')]
class NewsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function news(FoNewsRepository $newsRepository): Response
    {
        return $this->render('user/pages/news/index.html.twig', [
            'data' => $newsRepository->findBy(['visibility' => NewsVisibility::All], ['createdAt' => 'ASC'])
        ]);
    }

    #[Route('/{slug}', name: 'read')]
    public function read(FoNews $obj): Response
    {
        return $this->render('user/pages/news/read.html.twig', [
            'elem' => $obj
        ]);
    }
}
