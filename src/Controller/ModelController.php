<?php

namespace App\Controller;

use App\Repository\{ModelRepository, BrandRepository};
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[route("api/model/{brand}", name: "app_api_")]
final class ModelController extends AbstractController
{
    public function __construct(
        private ModelRepository $modelRepository,
        private BrandRepository $brandRepository,
        private SerializerInterface $serializer,
        )
    {
    }

    #[Route(name: 'model', methods: "GET")]
    #[OA\Get(
        path: '/api/model/{brand}',
        summary: 'Afficher toutes les modèles d\'une marque par son nom',
        parameters: [
            new OA\Parameter(
                name: 'brand',
                in: 'path',
                required: true,
                description: 'nom de la marque dont les modèles sont à afficher',
                schema: new OA\Schema(type: 'string', example: 'Smart')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Modèles trouvés avec succès',
            ),
            new OA\Response(
                response: 404,
                description: 'Marque et/ou modèles non trouvé(e)(s)'
            )
        ]
    )]
    public function showAll($brand): Response
    {
        $brandEntity = $this->brandRepository->findBy(["brand" => $brand]);
        if (!$brandEntity) {
            return new JsonResponse(['error' => 'Marque et/ou modèles non trouvé(e)(s)'], Response::HTTP_NOT_FOUND);
        }
        
        $models = $this->modelRepository->findBy(["brand" => $brandEntity]);
        $responseData = $this->serializer->serialize($models,"json", ['groups' => ['model']]);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }
}
