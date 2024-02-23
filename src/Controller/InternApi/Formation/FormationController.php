<?php

namespace App\Controller\InternApi\Formation;

use App\Entity\Formation\FoFormation;
use App\Repository\Formation\FoFormationRepository;
use App\Repository\Formation\FoNewsRepository;
use App\Service\ApiResponse;
use App\Service\Data\DataFormation;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/intern/api/fo/formations', name: 'intern_api_fo_formations_')]
class FormationController extends AbstractController
{
    #[Route('/list', name: 'list', options: ['expose' => true], methods: 'GET')]
    public function list(FoNewsRepository $repository, ApiResponse $apiResponse): Response
    {
        return $apiResponse->apiJsonResponse($repository->findAll(), FoFormation::LIST);
    }

    public function submitForm($type, FoFormationRepository $repository, FoFormation $obj, Request $request, ApiResponse $apiResponse,
                               ValidatorService $validator, DataFormation $dataEntity): JsonResponse
    {
        $data = json_decode($request->get('data'));
        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $obj = $dataEntity->setDataFormation($obj, $data);

        if($type === "update"){
            $obj->setUpdatedAt(new \DateTime());
        }

        $noErrors = $validator->validate($obj);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $repository->save($obj, true);
        return $apiResponse->apiJsonResponse($obj, FoFormation::LIST);
    }

    #[Route('/create', name: 'create', options: ['expose' => true], methods: 'POST')]
    #[IsGranted('ROLE_MANAGER')]
    public function create(Request $request, ApiResponse $apiResponse, ValidatorService $validator,
                           DataFormation $dataEntity, FoFormationRepository $repository): Response
    {
        return $this->submitForm("create", $repository, new FoFormation(), $request, $apiResponse, $validator, $dataEntity);
    }

    #[Route('/update/{id}', name: 'update', options: ['expose' => true], methods: 'PUT')]
    #[IsGranted('ROLE_MANAGER')]
    public function update(Request $request, FoFormation $obj, ApiResponse $apiResponse, ValidatorService $validator,
                           DataFormation $dataEntity, FoFormationRepository $repository): Response
    {
        return $this->submitForm("update", $repository, $obj, $request, $apiResponse, $validator, $dataEntity);
    }

    #[Route('/delete/{id}', name: 'delete', options: ['expose' => true], methods: 'DELETE')]
    #[IsGranted('ROLE_MANAGER')]
    public function delete(FoFormation $obj, FoFormationRepository $repository, ApiResponse $apiResponse): Response
    {
        $repository->remove($obj, true);

        return $apiResponse->apiJsonResponseSuccessful("ok");
    }
}
