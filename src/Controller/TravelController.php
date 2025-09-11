<?php

namespace App\Controller;

use App\Entity\{Travel, TravelUser, User};
use App\Repository\{CarRepository};
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[route("api/travel", name: "app_api_")]
final class TravelController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private CarRepository $carRepository,
        private SerializerInterface $serializer,
        )
    {
    }

    #[Route('/add', name: 'travel_add', methods: ['POST'])]
    #[OA\Post(
        path: '/api/travel/add',
        summary: 'Ajout d\'un nouveau voyage',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données du voyage à ajouter',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'travelPlace', type: 'integer', example: 3),
                    new OA\Property(property: 'price', type: 'integer', example: 5),
                    new OA\Property(property: 'depDateTime', type: 'string', format:"date-time", example: '03/09/2025 10:30'),
                    new OA\Property(property: 'depAddress', type: 'string', example: '2 Rte de Saint-Sigismond, 85490 Benet'),
                    new OA\Property(property: 'depGeoX', type: 'number', example: -0.685887),
                    new OA\Property(property: 'depGeoY', type: 'number', example: 46.359344),
                    new OA\Property(property: 'arrDateTime', type: 'string', format:"date-time", example: '03/09/2025 10:34'),
                    new OA\Property(property: 'arrAddress', type: 'string', example: '70 Rue du Marais, 85490 Benet'),
                    new OA\Property(property: 'arrGeoX', type: 'number', example: -0.664917),
                    new OA\Property(property: 'arrGeoY', type: 'number', example: 46.35904),
                    new OA\Property(property: 'car', type: 'integer', example: 2)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'voyage créé avec succès',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'eco', type: 'boolean', example: true),
                        new OA\Property(property: 'travelPlace', type: 'integer', example: 3),
                        new OA\Property(property: 'availablePlace', type: 'integer', example: 3),
                        new OA\Property(property: 'price', type: 'integer', example: 5),
                        new OA\Property(property: 'state', type: 'string', example: 'à venir'),
                        new OA\Property(property: 'depDateTime', type: 'string', format:"date-time", example: '03/09/2025 10:30'),
                        new OA\Property(property: 'depAddress', type: 'string', example: '2 Rte de Saint-Sigismond, 85490 Benet'),
                        new OA\Property(property: 'depGeoX', type: 'number', example: -0.685887),
                        new OA\Property(property: 'depGeoY', type: 'number', example: 46.359344),
                        new OA\Property(property: 'arrDateTime', type: 'string', format:"date-time", example: '03/09/2025 10:34'),
                        new OA\Property(property: 'arrAddress', type: 'string', example: '70 Rue du Marais, 85490 Benet'),
                        new OA\Property(property: 'arrGeoX', type: 'number', example: -0.664917),
                        new OA\Property(property: 'arrGeoY', type: 'number', example: 46.35904),
                        new OA\Property(property: 'createdAt', type: 'string', format:"date-time"),
                        new OA\Property(property: 'car', type: 'integer', example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits ',
            )
        ]
    )]
    public function addTravel(Request $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $travel = $this->serializer->deserialize($request->getContent(), Travel::class, "json");

        $travel->setAvailablePlace($travel->getTravelPlace());
        $travel->setStatus("à venir");

        $travel->setCreatedAt(new \DateTimeImmutable());

        $data = json_decode($request->getContent(), true);
        $carRequest = $data['car'];
        $car = $this->carRepository->findOneBy(["id" => $carRequest]);
        $travel->setCar($car);

        if ($car->getEnergy()->getEnergy()==="Electrique") {
            $travel->setEco(true);
        } else {
            $travel->setEco(false);
        }

        $travelUser = new TravelUser();
        $travelUser->setUser($user);
        $travelUser->setTravel($travel);
        $travelUser->setTravelRole("driver");

        $this->manager->persist($travel);
        $this->manager->persist($travelUser);
        $this->manager->flush();
        return $this->json(["message" => "Voyage créé avec succès"], Response::HTTP_CREATED);

    }
}
