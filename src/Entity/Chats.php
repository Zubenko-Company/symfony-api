<?php

namespace App\Entity;

use App\Repository\ChatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatsRepository::class)]
class Chats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: UsersChats::class)]
    private $usersChats;

    public function __construct()
    {
        $this->usersChats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|UsersChats[]
     */
    public function getUsersChats(): Collection
    {
        return $this->usersChats;
    }

    public function addUsersChat(UsersChats $usersChat): self
    {
        if (!$this->usersChats->contains($usersChat)) {
            $this->usersChats[] = $usersChat;
            $usersChat->setChat($this);
        }

        return $this;
    }

    public function removeUsersChat(UsersChats $usersChat): self
    {
        if ($this->usersChats->removeElement($usersChat)) {
            // set the owning side to null (unless already changed)
            if ($usersChat->getChat() === $this) {
                $usersChat->setChat(null);
            }
        }

        return $this;
    }
}
