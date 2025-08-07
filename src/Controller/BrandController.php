<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[route("api/brand", name: "app_api_")]
final class BrandController extends AbstractController
{
    public function __construct(
        private BrandRepository $repository,
        private SerializerInterface $serializer,
        )
    {
    }

    #[Route(name: 'brand', methods: "GET")]
    #[OA\Get(
        path: '/api/brand',
        summary: 'Affiche toutes les marques pouvant être sélectionnées lors de la création des véhicules',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des marques disponibles lors de la création d\'un véhicule',
            )
        ]
    )]
    public function showAll(): Response
    {
        $brands = $this->repository->findAll();
        $responseData = $this->serializer->serialize($brands,"json", ['groups' => ['brand']]);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }
}
