<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckPassportSubscriber implements EventSubscriberInterface
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getClient(CheckPassportEvent $event): void
    {
        $entityManager = $this->doctrine->getManager();
        $clientId = $event->getPassport()->getAttributes()['oauthClientId'];
        $userId = $event->getPassport()->getUser()->getId();

        /** @var User $user */
        $user = $this->doctrine->getRepository(User::class)->find($userId);
        $user->setClientId($clientId);

        $entityManager->persist($user);
        $entityManager->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['getClient', 256],
        ];
    }
}