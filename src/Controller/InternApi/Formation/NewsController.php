<?php

namespace App\Controller\InternApi\Formation;

use App\Entity\Formation\FoNews;
use App\Repository\Formation\FoNewsRepository;
use App\Service\ApiResponse;
use App\Service\Data\DataFormation;
use App\Service\FileUploader;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/intern/api/fo/news', name: 'intern_api_fo_news_')]
class NewsController extends AbstractController
{
    #[Route('/list', name: 'list', options: ['expose' => true], methods: 'GET')]
    public function list(FoNewsRepository $repository, ApiResponse $apiResponse): Response
    {
        return $apiResponse->apiJsonResponse($repository->findAll(), FoNews::LIST);
    }

    public function submitForm($type, FoNewsRepository $repository, FoNews $obj, Request $request, ApiResponse $apiResponse,
                               ValidatorService $validator, DataFormation $dataEntity, FileUploader $fileUploader): JsonResponse
    {
        $data = json_decode($request->get('data'));
        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $obj = $dataEntity->setDataNews($obj, $data, $repository);

        if($type === "update"){
            $obj->setUpdatedAt(new \DateTime());
        }

        $noErrors = $validator->validate($obj);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $file = $request->files->get('file');
        if ($file) {
            $fileName = $fileUploader->replaceFile($file, FoNews::FOLDER, $obj->getFile());
            $obj->setFile($fileName);
        }


        $repository->save($obj, true);
        return $apiResponse->apiJsonResponse($obj, FoNews::LIST);
    }

    #[Route('/create', name: 'create', options: ['expose' => true], methods: 'POST')]
    #[IsGranted('ROLE_MANAGER')]
    public function create(Request $request, ApiResponse $apiResponse, ValidatorService $validator,
                           DataFormation $dataEntity, FoNewsRepository $repository, FileUploader $fileUploader): Response
    {
        return $this->submitForm("create", $repository, new FoNews(), $request, $apiResponse, $validator, $dataEntity, $fileUploader);
    }

    #[Route('/update/{id}', name: 'update', options: ['expose' => true], methods: 'POST')]
    #[IsGranted('ROLE_MANAGER')]
    public function update(Request $request, FoNews $obj, ApiResponse $apiResponse, ValidatorService $validator,
                           DataFormation $dataEntity, FoNewsRepository $repository, FileUploader $fileUploader): Response
    {
        return $this->submitForm("update", $repository, $obj, $request, $apiResponse, $validator, $dataEntity, $fileUploader);
    }

    #[Route('/delete/{id}', name: 'delete', options: ['expose' => true], methods: 'DELETE')]
    #[IsGranted('ROLE_MANAGER')]
    public function delete(FoNews $obj, FoNewsRepository $repository, ApiResponse $apiResponse): Response
    {
        $repository->remove($obj, true);

        return $apiResponse->apiJsonResponseSuccessful("ok");
    }
}
