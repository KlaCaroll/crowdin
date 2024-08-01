<?php

namespace App\Entity;

use App\Repository\NotificationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $chat_id = null;

    #[ORM\Column]
    private ?int $author_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $message_count = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    public function setChatId(int $chat_id): static
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): static
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getMessageCount(): ?int
    {
        return $this->message_count;
    }

    public function setMessageCount(?int $message_count): static
    {
        $this->message_count = $message_count;

        return $this;
    }
}
