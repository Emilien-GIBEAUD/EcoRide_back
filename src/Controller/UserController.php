<?php

namespace App\Controller;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    #[OA\Post(
        path: '/api/user/registration',
        summary: 'Inscription d\'un nouvel utilisateur',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de l\'utilisateur à inscrire',
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['firstName', 'lastName', 'pseudo', 'email', 'password'],
                    properties: [
                        new OA\Property(property: 'firstName', type: 'string', example: 'prénom'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'nom'),
                        new OA\Property(property: 'pseudo', type: 'string', example: 'pseudo'),
                        new OA\Property(property: 'avatarFile', type: 'string', format: 'binary'),
                        new OA\Property(property: 'email', type: 'string', example: 'adresse@email.com'),
                        new OA\Property(property: 'password', type: 'string', example: 'Mdp@12carMIN')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur inscrit avec succès',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'user', type: 'string', example: 'adresse@email.com'),
                        new OA\Property(property: 'apiToken', type: 'string', example: '31a023e212..........3806eb9378'),
                        new OA\Property(
                            property: 'roles',
                            type: 'array',
                            items: new OA\Items(type: 'string', example: 'ROLE_USER')
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Requête invalide'
            )
        ]
    )]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        ): JsonResponse
    {
        $user = new User();
        $user->setFirstName($request->request->get('firstName'));
        $user->setLastName($request->request->get('lastName'));
        $user->setPseudo($request->request->get('pseudo'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('password')));
        $user->setCredit(20);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setActive(1);

        $avatarFile = $request->files->get('avatarFile');
        if ($avatarFile) {
            $user->setAvatarFileTemp($avatarFile);
            // validation manuelle du fichier (upload du fichier avec vich/uploader mais sans utiliser de formulaire symfony)
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
                }
                return $this->json(["message" => "Erreur lors de l'upload du fichier"], Response::HTTP_BAD_REQUEST);
            }
        }

        $this->manager->persist($user);
        $this->manager->flush();
        return $this->json(["message" => "Utilisateur créé avec succès"], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    #[OA\Post(
        path: '/api/user/login',
        summary: 'Connexion d\'un utilisateur',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de l\'utilisateur à connecter (le compte doit préalablement avoir été créé avec /api/registration)',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'username', type: 'string', example: 'adresse@email.com'),
                    // /app/vendor/symfony/security-http/Authenticator/JsonLoginAuthenticator.php demande "username" et non "email"
                    new OA\Property(property: 'password', type: 'string', example: 'Mdp@12carMIN')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur connecté',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'user', type: 'string', example: 'adresse@email.com'),
                        new OA\Property(property: 'apiToken', type: 'string', example: '31a023e212..........3806eb9378'),
                        new OA\Property(
                            property: 'roles',
                            type: 'array',
                            items: new OA\Items(type: 'string', example: 'ROLE_USER')
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Mail et/ou mot de passe inconnus'
            )
        ]
    )]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'Mail et/ou mot de passe inconnus'], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }
    
    #[route("/me", name: "me", methods: "GET")]
    #[OA\Get(
        path: '/api/user/me',
        summary: 'Récupération du profil de l\'utilisateur connecté',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profil de l\'utilisateur connecté',
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits pour accéder à cet utilisateur',
            )
        ]
    )]
    public function me(#[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits pour accéder à cet utilisateur'], Response::HTTP_UNAUTHORIZED);
        }

        $responseData = $this->serializer->serialize($user, "json", ['groups' => ['user']]);
        $data = json_decode($responseData, true);
        unset($data['password'], $data['avatarFileTemp'], $data['userIdentifier'], $data['id']);
        $responseData = json_encode($data);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        
    }

    #[route("/edit", name: "edit", methods: "POST")]
    #[OA\POST(
        path: '/api/user/edit',
        summary: 'Modification d\'un profil utilisateur (un ou plusieurs champs)',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Champs éventuels à mettre à jour (laissez vide si pas mis à jour).',
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'firstName', type: 'string', example: 'prénom'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'nom'),
                        new OA\Property(property: 'pseudo', type: 'string', example: 'pseudo'),
                        new OA\Property(property: 'avatarFile', type: 'string', format: 'binary'),
                        new OA\Property(property: 'usageRole', type: 'string', example: '["driver"]', description: 'Rôle d\'utilisation de l\'utilisateur : ["driver"]=> conducteur et passager, []=> passager uniquement')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur modifié avec succès',
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits pour modifier cet utilisateur',
            )
        ]
    )]
    public function edit(#[CurrentUser] ?User $user, Request $request, ValidatorInterface $validator): JsonResponse
    {
        if ($user) {
            if ($request->request->has('firstName')) {
                $user->setFirstName($request->request->get('firstName'));
            }
            if ($request->request->has('lastName')) {
                $user->setLastName($request->request->get('lastName'));
            }
            if ($request->request->has('pseudo')) {
                $user->setPseudo($request->request->get('pseudo'));
            }
            if ($request->request->has('usageRole')) {
                $usageRole = $request->request->get('usageRole');
                if ($usageRole === "driver" || $usageRole === "") { // si passage par l'API
                    $user->setUsageRole($usageRole === "driver" ? ["driver"] : []);
                }
                if ($usageRole === '["driver"]' || $usageRole === '[]') { // si passage par le front
                    $usageRoleDecoded = json_decode($usageRole, true);
                    $user->setUsageRole($usageRoleDecoded);
                }
            }

            $avatarFile = $request->files->get('avatarFile');
            if ($avatarFile) {
                $user->setAvatarFileTemp($avatarFile);
                // validation manuelle du fichier (upload du fichier avec vich/uploader mais sans utiliser de formulaire symfony)
                $errors = $validator->validate($user);
                if (count($errors) > 0) {
                    $errorMessages = [];
                    foreach ($errors as $error) {
                        $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
                    }
                    return $this->json(["message" => "Erreur lors de l'upload du fichier"], Response::HTTP_BAD_REQUEST);
                }
            }

            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->manager->flush();
            return new JsonResponse(["message" => "Utilisateur modifié avec succès"], Response::HTTP_OK);
        }
        return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits pour modifier cet utilisateur'], Response::HTTP_UNAUTHORIZED);
    }
}

