<?php

namespace App\Controller;
use App\Service\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ChatsRepository;

class ChatsController extends AbstractController
{
    private $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    #[Route('/my_chats', name: 'my_chats')]
    public function index(ChatsRepository $chatrepo): Response
    {   
        $user_connected = $this->getUser();
        $chats = $chatrepo->findByUser($user_connected);
        $chatdata = $this->chatService->getChatsWithOtherUser($user_connected);
        return $this->render('chats/index.html.twig', [
            'chatdata' => $chatdata,
        ]);
    }
}
