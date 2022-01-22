<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use League\Bundle\OAuth2ServerBundle\Entity\Client;

class UserController extends AbstractController
{
    /**
     * Get user info
     *
     * Get user info
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns user info",
     *     @Model(type=User::class)
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     */
    #[Route('/api/me', name: 'getUser', methods: ['GET'], format: "json")]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        /** @var User $user */
        $user = $this->getUser();

        $sql = "SELECT * FROM symfony.oauth2_client where identifier='{$user->getClientId()}'";

        $conn = $entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $response = $stmt->executeQuery();

        try {
            $clientName = $response->fetchAll()[0]['name'];
        } catch (\Exception $e) {
            $clientName = null;
        }
        $response = [
            'email' => $user->getEmail(),
            'client' => $clientName,
        ];

        return new Response(json_encode($response), Response::HTTP_OK);
    }

    private function getBearerToken(Request $request): string
    {
        preg_match('/Bearer\s(\S+)/', $request->headers->get('Authorization'), $matches);

        return $matches[1];
    }
}
