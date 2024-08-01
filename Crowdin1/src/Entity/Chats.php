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
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user1 = null;

    #[ORM\Column]
    private ?int $user2 = null;

    /**
     * @var Collection<int, Messages>
     */
    #[ORM\OneToMany(targetEntity: Messages::class, mappedBy: 'chat')]
    private Collection $messages;

    #[ORM\Column(nullable: true)]
    private ?int $messages_count = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser1(): ?int
    {
        return $this->user1;
    }

    public function setUser1(int $user1): static
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getUser2(): ?int
    {
        return $this->user2;
    }

    public function setUser2(int $user2): static
    {
        $this->user2 = $user2;

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
    }

    public function getMessagesCount(): ?int
    {
        return $this->messages_count;
    }

    public function setMessagesCount(?int $messages_count): static
    {
        $this->messages_count = $messages_count;

        return $this;
    }
}
