<?php

namespace App\Controller;

use App\Repository\EnergyRepository;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[route("api/energy", name: "app_api_")]
final class EnergyController extends AbstractController
{
    public function __construct(
        private EnergyRepository $repository,
        private SerializerInterface $serializer,
        )
    {
    }

    #[Route(name: 'energy', methods: "GET")]
    #[OA\Get(
        path: '/api/energy',
        summary: 'Affiche toutes les énergies pouvant être sélectionnées lors de la création des véhicules',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des énergies disponibles lors de la création d\'un véhicule',
            )
        ]
    )]
    public function showAll(): Response
    {
        $energies = $this->repository->findAll();
        $responseData = $this->serializer->serialize($energies,"json", ['groups' => ['energy']]);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }
}
