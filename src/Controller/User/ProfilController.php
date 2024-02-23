<?php

namespace App\Controller\User;

use App\Entity\Main\User;
use App\Repository\Main\UserRepository;
use App\Service\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/espace-membre/mon-compte', name: 'user_profil_')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'index', options: ['expose' => true])]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/pages/profil/index.html.twig', [
            'elem' => $user
        ]);
    }

    #[Route('/modifier', name: 'update', options: ['expose' => true], methods: ['GET', 'PUT'])]
    public function update(Request $request, UserRepository $repository,
                           SerializerInterface $serializer, ApiResponse $apiResponse): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($request->isMethod('POST')){
            $data = json_decode($request->getContent());

            $repository->save($user, true);
            return $apiResponse->apiJsonResponseSuccessful("Paramètres mis à jours");
        }

        $obj = $serializer->serialize($user, 'json', ['groups' => User::FORM]);

        return $this->render('user/pages/profil/update.html.twig', [
            'obj' => $obj
        ]);
    }
}
