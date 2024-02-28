<?php

namespace App\Controller\User;

use App\Entity\Main\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-membre/inscriptions', name: 'user_orders_')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/pages/orders/index.html.twig', ['elem' => $user]);
    }
}
