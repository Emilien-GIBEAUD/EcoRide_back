<?php

namespace App\Controller;

use App\Entity\{Car, User};
use App\Repository\{BrandRepository, CarRepository, ColorRepository, EnergyRepository, ModelRepository};
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[route("api/car", name: "app_api_")]
final class CarController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private BrandRepository $brandRepository,
        private CarRepository $carRepository,
        private ColorRepository $colorRepository,
        private EnergyRepository $energyRepository,
        private ModelRepository $modelRepository,
        private SerializerInterface $serializer,
        )
    {
    }

    #[Route('/add', name: 'car_add', methods: "POST")]
    #[OA\Post(
        path: '/api/car/add',
        summary: 'Ajout d\'un nouveau véhicule',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données du véhicule à ajouter',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'brand', type: 'string', example: 'Smart'),
                    new OA\Property(property: 'model', type: 'string', example: 'Forfour'),
                    new OA\Property(property: 'color', type: 'string', example: 'Gris'),
                    new OA\Property(property: 'energy', type: 'string', example: 'Diesel'),
                    new OA\Property(property: 'licencePlate', type: 'string', example: 'AA 000 AA'),
                    new OA\Property(property: 'firstRegistration', type: 'string', format:"date", example: '01/01/2005'),
                    new OA\Property(property: 'placeNb', type: 'integer', example: 2)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Véhicule créé avec succès',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'model', type: 'string', example: 'Forfour'),
                    new OA\Property(property: 'color', type: 'string', example: 'Gris'),
                    new OA\Property(property: 'energy', type: 'string', example: 'Diesel'),
                    new OA\Property(property: 'licencePlate', type: 'string', example: 'AA 000 AA'),
                    new OA\Property(property: 'firstRegistration', type: 'string', format:"date", example: '01/01/2005'),
                    new OA\Property(property: 'placeNb', type: 'integer', example: 3),
                    new OA\Property(property: 'createdAt', type: 'string', format:"date-time")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits ',
            )
        ]
    )]
    public function addCar(Request $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $car = $this->serializer->deserialize($request->getContent(), Car::class, "json");
        $car->setCreatedAt(new \DateTimeImmutable());
        $car->setUser($user);
        $car->setMain(false);
        $car->setActive(true);

        $data = json_decode($request->getContent(), true);

        $colorRequest = $data['color'];
        $color = $this->colorRepository->findOneBy(["color" => $colorRequest]);
        $car->setColor($color);
        $energyRequest = $data['energy'];
        $energy = $this->energyRepository->findOneBy(["energy" => $energyRequest]);
        $car->setEnergy($energy);

        $brandRequest = $data['brand'];
        $brand = $this->brandRepository->findOneBy(["brand" => $brandRequest]);
        $modelRequest = $data['model'];
        $model = $this->modelRepository->findOneBy(["model" => $modelRequest, "brand" => $brand]);
        $car->setModel($model);

        $this->manager->persist($car);
        $this->manager->flush();
        return $this->json(["message" => "Véhicule créé avec succès"], Response::HTTP_CREATED);
    }

    #[Route('/showAll/{email}', name: 'car_showAll', methods: "GET")]
    #[OA\Get(
        path: '/api/car/showAll/{email}',
        summary: 'Affiche tous les véhicules d\'un utilisateur',
        parameters: [
            new OA\Parameter(
                name: 'email',
                in: 'path',
                required: true,
                description: 'email de l\'utilisateur propriétaire des véhicules à afficher',
                schema: new OA\Schema(type: 'string', example: "adresse@email.com")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Véhicules trouvés avec succès',
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits',
            ),
            new OA\Response(
                response: 404,
                description: 'Véhicule(s) non trouvé(s)'
            )
        ]
    )]
    public function showAll(#[CurrentUser] ?User $user, $email): Response
    {
        if ($user === null || $user->getEmail() !== $email) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $cars = $this->carRepository->findBy(["user" => $user, "active" => true]);
        if ($cars === null) {
            return new JsonResponse(['message' => 'Véhicule(s) non trouvé(s)'], Response::HTTP_NOT_FOUND);
        }
        $responseData = $this->serializer->serialize($cars,"json", ['groups' => ['car']]);

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/show/{email}', name: 'car_show', methods: "GET")]
    #[OA\Get(
        path: '/api/car/show/{email}',
        summary: 'Affiche le véhicule principal d\'un utilisateur (ou le premier véhicule à défault de véhicule principal déclaré)',
        parameters: [
            new OA\Parameter(
                name: 'email',
                in: 'path',
                required: true,
                description: 'email de l\'utilisateur propriétaire du véhicule à afficher',
                schema: new OA\Schema(type: 'string', example: "adresse@email.com")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Véhicule trouvé avec succès',
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits',
            ),
            new OA\Response(
                response: 404,
                description: 'Véhicule non trouvé'
            )
        ]
    )]
    public function show(#[CurrentUser] ?User $user, $email): Response
    {
        if ($user === null || $user->getEmail() !== $email) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $car = $this->carRepository->findOneBy(["user" => $user, "main" => true, "active" => true]);
        if ($car === null) {
            $car = $this->carRepository->findOneBy(["user" => $user, "active" => true]);
            if ($car === null) {
                return new JsonResponse([], Response::HTTP_OK);
            }
        }
        $responseData = $this->serializer->serialize($car,"json", ['groups' => ['car']]);

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/edit/{id}', name: 'car_edit', methods: "POST")]
    #[OA\Post(
        path: '/api/car/edit/{id}',
        summary: 'Edite un véhicule par son id',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'id du véhicule à éditer',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données du véhicule à modifier',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'brand', type: 'string', example: 'Smart'),
                    new OA\Property(property: 'model', type: 'string', example: 'Forfour'),
                    new OA\Property(property: 'color', type: 'string', example: 'Gris'),
                    new OA\Property(property: 'energy', type: 'string', example: 'Diesel'),
                    new OA\Property(property: 'licencePlate', type: 'string', example: 'AA 000 AA'),
                    new OA\Property(property: 'firstRegistration', type: 'string', format:"date", example: '01/01/2005'),
                    new OA\Property(property: 'placeNb', type: 'integer', example: 2)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Véhicule modifié avec succès',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'model', type: 'string', example: 'Forfour'),
                    new OA\Property(property: 'color', type: 'string', example: 'Gris'),
                    new OA\Property(property: 'energy', type: 'string', example: 'Diesel'),
                    new OA\Property(property: 'licencePlate', type: 'string', example: 'AA 000 AA'),
                    new OA\Property(property: 'firstRegistration', type: 'string', format:"date", example: '01/01/2005'),
                    new OA\Property(property: 'placeNb', type: 'integer', example: 3),
                    new OA\Property(property: 'createdAt', type: 'string', format:"date-time")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits ',
            ),
            new OA\Response(
                response: 404,
                description: 'Véhicule non trouvé'
            )
        ]
    )]
    public function edit(Request $request, #[CurrentUser] ?User $user, $id): Response
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $car = $this->carRepository->findOneBy(["id" => $id]);
        if ($car === null) {
            return new JsonResponse(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $car->setUpdatedAt(new \DateTimeImmutable());

        $data = json_decode($request->getContent(), true);

        $car->setLicencePlate($data['licencePlate']);
        $car->setPlaceNb($data['placeNb']);
        $firstRegistration = \DateTimeImmutable::createFromFormat('d/m/Y', $data['firstRegistration']);
        $car->setFirstRegistration($firstRegistration);

        $colorRequest = $data['color'];
        $color = $this->colorRepository->findOneBy(["color" => $colorRequest]);
        $car->setColor($color);
        $energyRequest = $data['energy'];
        $energy = $this->energyRepository->findOneBy(["energy" => $energyRequest]);
        $car->setEnergy($energy);

        $brandRequest = $data['brand'];
        $brand = $this->brandRepository->findOneBy(["brand" => $brandRequest]);
        $modelRequest = $data['model'];
        $model = $this->modelRepository->findOneBy(["model" => $modelRequest, "brand" => $brand]);
        $car->setModel($model);

        $this->manager->flush();
        return $this->json(["message" => "Véhicule modifié avec succès"], Response::HTTP_OK);
    }

}
