<?php

namespace App\Controller;

use App\Entity\{Car, User};
use App\Repository\{BrandRepository, CarRepository, ColorRepository, EnergyRepository, ModelRepository};
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/add', name: 'add', methods: "POST")]
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
}
