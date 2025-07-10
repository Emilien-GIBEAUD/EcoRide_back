<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[route("api/user", name: "app_api_")]
final class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer,
        )
    {
    }

    #[route("/registration", name: "registration", methods: "POST")]
    // #[OA\Post(
    //     path: '/api/user/registration',
    //     summary: 'Inscription d\'un nouvel utilisateur',
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         description: 'Données de l\'utilisateur à inscrire',
    //         content: new OA\JsonContent(
    //             type: 'object',
    //             properties: [
    //                 new OA\Property(property: 'firstName', type: 'string', example: 'prénom'),
    //                 new OA\Property(property: 'lastName', type: 'string', example: 'nom'),
    //                 new OA\Property(property: 'email', type: 'string', example: 'adresse@email.com'),
    //                 new OA\Property(property: 'password', type: 'string', example: 'Mdp@13charMIN')
    //             ]
    //         )
    //     ),
    //     responses: [
    //         new OA\Response(
    //             response: 201, 
    //             description: 'Utilisateur inscrit avec succès',
    //             content: new OA\JsonContent(
    //                 type: 'object',
    //                 properties: [
    //                     new OA\Property(property: 'user', type: 'string', example: 'adresse@email.com'),
    //                     new OA\Property(property: 'apiToken', type: 'string', example: '31a023e212f116124a36af14ea0c1c3806eb9378'),
    //                     new OA\Property(
    //                         property: 'roles', 
    //                         type: 'array', 
    //                         items: new OA\Items(type: 'string', example: 'ROLE_USER')
    //                     )
    //                 ]
    //             )
    //         )
    //     ]
    // )]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, "json");
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCredit(20);
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(
            ["user" => $user->getUserIdentifier(),
                "apiToken" => $user->getApiToken(),
                "roles" => $user->getRoles(),
            ],
            Response::HTTP_CREATED
        );
    }
}
