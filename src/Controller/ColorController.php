<?php

namespace App\Controller;

use App\Repository\ColorRepository;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[route("api/color", name: "app_api_")]
final class ColorController extends AbstractController
{
    public function __construct(
        private ColorRepository $repository,
        private SerializerInterface $serializer,
        )
    {
    }

    #[Route(name: 'color', methods: "GET")]
    #[OA\Get(
        path: '/api/color',
        summary: 'Affiche toutes les couleurs pouvant être sélectionnées lors de la création des véhicules',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des couleurs disponibles lors de la création d\'un véhicule',
            )
        ]
    )]
    public function showAll(): Response
    {
        $colors = $this->repository->findAll();
        $responseData = $this->serializer->serialize($colors,"json", ['groups' => ['color']]);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }
}
