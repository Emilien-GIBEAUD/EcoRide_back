<?php

namespace App\Controller;

use App\Entity\{Travel, TravelUser, User};
use App\Repository\{CarRepository, TravelRepository, TravelUserRepository};
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
        private TravelUserRepository $travelUserRepository,
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

    #[Route('/list/{email}', name: 'travel_list', methods: ['GET'])]
    #[OA\Get(
        path: '/api/travel/list/{email}',
        summary: 'Affiche tous les voyages d\'un utilisateur',
        parameters: [
            new OA\Parameter(
                name: 'email',
                in: 'path',
                required: true,
                description: 'email de l\'utilisateur propriétaire des voyages à afficher',
                schema: new OA\Schema(type: 'string', example: "adresse@email.com")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Voyage(s) trouvé(s) avec succès',
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits',
            ),
            new OA\Response(
                response: 404,
                description: 'Voyage(s) non trouvé(s)'
            )
        ]
    )]
    public function showAll(#[CurrentUser] ?User $user, $email): Response
    {
        if ($user === null || $user->getEmail() !== $email) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $travels = $this->travelUserRepository->findBy(["user" => $user]);
        if ($travels === null) {
            return new JsonResponse(['message' => 'Voyage(s) non trouvé(s)'], Response::HTTP_NOT_FOUND);
        }
        $responseData = $this->serializer->serialize($travels,"json", ['groups' => ['travel']]);

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }


// exemple chatGPT suite discussion du 12/09/2025 pour routede résultats de recherche
// A étudier et adapter en temps voulu
// voir chatGPT si besoin pour la méthode de recherche dans TravelRepository
// GET /api/travel/results?date={date}&start={start}&end={end}

    #[Route('/search', name: 'travel_search', methods: ['GET'])]
    #[OA\Get(
        path: '/api/travel/search',
        summary: 'Recherche de voyages',
        description: 'Retourne la liste des voyages correspondant aux critères.',
        parameters: [
            new OA\QueryParameter(
                name: 'date',
                description: 'Date du voyage (format YYYY-MM-DD)',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-03-20')
            ),
            new OA\QueryParameter(
                name: 'depGeoX',
                description: 'Ville de départ : coordonnée géocodée X',
                required: true,
                schema: new OA\Schema(type: 'number', example: -0.466)
            ),
            new OA\QueryParameter(
                name: 'depGeoY',
                description: 'Ville de départ : coordonnée géocodée Y',
                required: true,
                schema: new OA\Schema(type: 'number', example: 46.327)
            ),
            new OA\QueryParameter(
                name: 'arrGeoX',
                description: 'Ville d\'arrivée : coordonnée géocodée X',
                required: true,
                schema: new OA\Schema(type: 'number', example: -1.2704)
            ),
            new OA\QueryParameter(
                name: 'arrGeoY',
                description: 'Ville d\'arrivée : coordonnée géocodée Y',
                required: true,
                schema: new OA\Schema(type: 'number', example: 47.09231)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des voyages trouvés',
                // content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Travel'))
            )
        ]
    )]
    public function search(Request $request, TravelRepository $repo): JsonResponse
    {
        // Récupération des paramètres de la requête
        $date      = $request->query->get('date');
        $depGeoX   = (float) $request->query->get('depGeoX');
        $depGeoY   = (float) $request->query->get('depGeoY');
        $arrGeoX   = (float) $request->query->get('arrGeoX');
        $arrGeoY   = (float) $request->query->get('arrGeoY');

        // Préparation des critères pour le repo
            $criteria = [
                'depDateTime' => new \DateTimeImmutable($date),
                'depGeoX'     => $depGeoX,
                'depGeoY'     => $depGeoY,
                'arrGeoX'     => $arrGeoX,
                'arrGeoY'     => $arrGeoY,
            ];

        $travels = $repo->search($criteria);

        return $this->json($travels, Response::HTTP_OK);
    }

}