<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OnUserResolveListener
{
    private UserPasswordHasherInterface $userPasswordHasher;

    private UserProviderInterface $userProvider;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserProviderInterface $userProvider
    ) {
        $this->userPasswordHasher = $passwordHasher;
        $this->userProvider = $userProvider;
    }

    public function onUserResolve(UserResolveEvent $event): void
    {
        /** @var User $user */
        $user = $this->userProvider->loadUserByIdentifier($event->getUsername());
        if(!$this->userPasswordHasher->isPasswordValid($user, $event->getPassword())) {
            return;
        }
        $event->setUser($user);
    }
}