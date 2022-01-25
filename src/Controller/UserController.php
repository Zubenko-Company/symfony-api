<?php

namespace App\Controller;

use App\Entity\RequestEntities\User\CreateUserEntity;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\ResponseShemas\UserInfo;
use App\Data\Roles;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     *     @Model(type=UserInfo::class)
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     */
    #[Route('/api/me', name: 'getUser', methods: ['GET'], format: "json")]
    public function getUserInfo(Request $request, ManagerRegistry $doctrine): Response
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
        }
        catch (\Exception $e) {
            $clientName = null;
        }

        $response = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'avatar' => $user->getAvatar(),
            'client' => $clientName,
        ];

        return new Response(json_encode($response), Response::HTTP_OK);
    }

    /**
     * Registrate user info
     *
     * Registrate user info
     *
     * @OA\RequestBody(
     *     @Model(type=CreateUserEntity::class)
     * )
     * @OA\Response(
     *     response=201,
     *     description="Create new user",
     * )
     * @OA\Tag(name="user")
     */
    #[Route('/registration', name: 'createUser', methods: ['POST'], format: "json")]
    public function createUser(
        Request                     $request,
        ManagerRegistry             $doctrine,
        SerializerInterface         $serializer,
        ValidatorInterface          $validator,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var CreateUserEntity $newUserCred */
        $newUserCred = $serializer->deserialize($request->getContent(), CreateUserEntity::class, 'json');
        $errors = $validator->validate($newUserCred);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return new Response(
                json_encode([
                    'messages' => $errorMessages
                ]),
                400
            );
        }

        if ($this->isEmailTaken($newUserCred->getEmail(), $doctrine)) {
            return new Response(
                json_encode([
                    'messages' => [
                        'email' => 'email is already taken('
                    ]
                ]),
                400
            );
        }

        $newUser = $this->fillUserEntity($newUserCred, $passwordHasher);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($newUser);
        $entityManager->flush();

        return new Response(
            json_encode(['status' => 'success']),
            201
        );
    }

    private function getBearerToken(Request $request): string
    {
        preg_match('/Bearer\s(\S+)/', $request->headers->get('Authorization'), $matches);

        return $matches[1];
    }

    private function isEmailTaken(string $email, ManagerRegistry $doctrine): bool
    {
        $user = $doctrine->getRepository(User::class)->findBy(['email' => $email]);

        return !$user == [];
    }

    private function fillUserEntity(CreateUserEntity $newUserCred, UserPasswordHasherInterface $passwordHasher): User
    {
        $user = (new User())->setFirstname($newUserCred->getFirstname())
            ->setLastname($newUserCred->getLastname())
            ->setEmail($newUserCred->getEmail())
            ->setRoles([Roles::USER_ROLE]);

        $hashedPass = $passwordHasher->hashPassword($user, $newUserCred->getPassword());

        return $user->setPassword($hashedPass);
    }
}
