<?php

namespace App\Entity;

use App\Repository\UsersChatsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersChatsRepository::class)]
class UsersChats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'usersChats')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Chats::class, inversedBy: 'usersChats')]
    private $chat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Chats::class
     */
    public function getChat()
    {
        return $this->chat;
    }

    public function setChat($chat): self
    {
        $this->chat = $chat;

        return $this;
    }
}
