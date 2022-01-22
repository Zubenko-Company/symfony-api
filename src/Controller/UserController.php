<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

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
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $currentUser = $serializer->serialize($this->getUser(), 'json');


        return new Response($currentUser, Response::HTTP_OK);
    }
}
