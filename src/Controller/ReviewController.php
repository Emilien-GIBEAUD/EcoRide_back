<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Review;
use App\Entity\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[route("api/review", name: "app_api_")]
class ReviewController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm,
        private SerializerInterface $serializer,
        )
    {}

    #[Route('/add', name: 'review_add', methods: ['POST'])]
    #[OA\Post(
        path: '/api/review/add',
        summary: 'Ajout d\'un nouveau commentaire sur le site',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données du commentaire à ajouter',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'note', type: 'integer', example: 5),
                    new OA\Property(property: 'comment', type: 'string', example: 'Merci José, et bravo pour le design avantgardiste de ton site !')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Commentaire ajouté avec succès',
            ),
            new OA\Response(
                response: 401,
                description: 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits ',
            )
        ]
    )]
    public function addReview(Request $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas connecté ou vous n\'avez pas les droits'], Response::HTTP_UNAUTHORIZED);
        }

        $review = $this->serializer->deserialize($request->getContent(), Review::class, "json");
        $review->setUserId($user->getId());
        $review->setPseudo($user->getPseudo());
        $review->setCreatedAt(new \DateTimeImmutable());

        $this->dm->persist($review);
        $this->dm->flush();
        return $this->json(["message" => "Commentaire ajouté avec succès"], Response::HTTP_CREATED);
    }

    #[Route('/list', name: 'review_list', methods: "GET")]
    #[OA\Get(
        path: '/api/review/list',
        summary: 'Affiche tous les commentaires laissés par les utilisateurs',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des commentaires laissés par les utilisateurs',
            )
        ]
    )]
    public function list(): Response
    {
        $reviews = $this->dm->getRepository(Review::class)->findAll();
        $responseData = $this->serializer->serialize($reviews,"json", ['groups' => ['comment']]);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/{userId}', name: 'review_byUserId', methods: "GET")]
    #[OA\Get(
        path: '/api/review/{userId}',
        summary: 'Affiche le commentaire laissé par l\'utilisateur',
        parameters: [
            new OA\Parameter(
                name: 'userId',
                in: 'path',
                required: true,
                description: 'id de l\'utilisateur',
                schema: new OA\Schema(type: 'number', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Le commentaire laissé par l\'utilisateur',
            )
        ]
    )]
    public function showByUserId($userId): Response
    {
        $reviews = $this->dm->getRepository(Review::class)->findBy(["userId" => $userId]);
        $responseData = $this->serializer->serialize($reviews,"json", ['groups' => ['comment']]);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }
}
