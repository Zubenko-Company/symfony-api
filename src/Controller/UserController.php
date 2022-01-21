<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/api/me', name: 'getUser', methods: ['GET'], format: "json")]
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $currentUser = $serializer->serialize($this->getUser(), 'json');

        dd($request);

        return new Response($currentUser, Response::HTTP_OK, );
    }
}
