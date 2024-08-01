<?php

namespace App\Service;

use App\Repository\ChatsRepository;
use App\Repository\UsersRepository;
use App\Repository\NotificationsRepository;
use App\Entity\Users;

class ChatService
{
    private $chatRepo;
    private $usersRepo;
    private $notifsrepo;

    public function __construct(ChatsRepository $chatRepo, UsersRepository $usersRepo, NotificationsRepository $notifsrepo)
    {
        $this->chatRepo = $chatRepo;
        $this->usersRepo = $usersRepo;
        $this->notifsrepo = $notifsrepo;
    }

    public function getChatsWithOtherUser(Users $user): array
    {
        $user_id = $user->getId();
        $chats = $this->chatRepo->findByUser($user_id);

        $chatData = [];
        foreach ($chats as $chat) {
            $otherUser_id = ($chat->getUser1() === $user_id) ? $chat->getUser2() : $chat->getUser1();
            $otherUser = $this->usersRepo->findUserById($otherUser_id)[0];
            $chat_id = $chat->getId();
            $notif = $this->notifsrepo->findByChatAndAuthor($chat_id, $user_id)[0];
            $chatData[] = [
                'chat' => $chat,
                'otherUser' => $otherUser,
                'notif' => $notif,
            ];
        }

        return $chatData;
    }
}
