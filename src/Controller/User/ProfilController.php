<?php

namespace App\Controller\User;

use App\Entity\Main\User;
use App\Repository\Main\UserRepository;
use App\Service\ApiResponse;
use App\Service\SanitizeData;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
                           SerializerInterface $serializer, ApiResponse $apiResponse,
                           SanitizeData $sanitizeData, ValidatorService $validator,
                           UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($request->isMethod('PUT')){
            $data = json_decode($request->getContent());

            $user = ($user)
                ->setEmail($sanitizeData->trimData($data->email))
                ->setFirstname($sanitizeData->trimData($data->firstname))
                ->setLastname($sanitizeData->trimData($data->lastname))
            ;

            if($data->password != ""){
                $user->setPassword($passwordHasher->hashPassword($user, $data->password));
            }

            $noErrors = $validator->validate($user);
            if ($noErrors !== true) {
                return $apiResponse->apiJsonResponseValidationFailed($noErrors);
            }

            $repository->save($user, true);
            return $apiResponse->apiJsonResponseSuccessful("Paramètres mis à jours");
        }

        $obj = $serializer->serialize($user, 'json', ['groups' => User::FORM]);

        return $this->render('user/pages/profil/update.html.twig', [
            'obj' => $obj
        ]);
    }
}
