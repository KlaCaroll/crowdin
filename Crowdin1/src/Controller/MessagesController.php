<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChatsRepository;
use App\Repository\MessagesRepository;
use App\Repository\UsersRepository;
use App\Repository\NotificationsRepository;
use App\Form\MessagesType;
use App\Entity\Chats;
use App\Entity\Messages;



class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'messages.index')]
    public function index(Request $request, EntityManagerInterface $em, ChatsRepository $chatrepo, UsersRepository $userrepo, 
    MessagesRepository $messagesrepo, NotificationsRepository $notifsrepo): Response
    {
        $message = new Messages();
        $chatId = $_GET['chatId'];
        $chat = $chatrepo->findById($chatId)[0];
        $message_count = $chat->getMessagesCount();
        $user_connected_id = $this->getUser()->getId();
        $notif = $notifsrepo->findByChatAndAuthor($chatId, $user_connected_id)[0];
        $notif_message_count = $message_count;
        $user1_id = $chat->getUser1();
        $user1 = $userrepo->findById($user1_id)[0];
        $user2_id = $chat->getUser2();
        $user2 = $userrepo->findById($user2_id)[0];
        $messages = $messagesrepo->findByChat($chat);
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $notif->setMessageCount($notif_message_count + 1);
            $chat->setMessagesCount($message_count + 1);
            $message->setChat($chat);
            $message->setAuthor($user_connected_id);
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('messages.index', ['chatId' => $chatId]);
        }
        return $this->render('messages/index.html.twig', [
            'form' => $form,
            'messages' => $messages,
            'user1' => $user1,
            'user2' => $user2,
            'user_connected_id' => $user_connected_id,
            'chatId' => $chatId,
        ]);

    }

    #[Route('/messages/delete', name: 'message.delete')]
    public function remove(EntityManagerInterface $em, MessagesRepository $messagesrepo, ChatsRepository $chatrepo,
    NotificationsRepository $notifsrepo): Response
    {
        $chatId = $_GET['chatId'];
        $chat = $chatrepo->findById($chatId)[0];
        $user_connected_id = $this->getUser()->getId();
        $notif = $notifsrepo->findByChatAndAuthor($chatId, $user_connected_id)[0];
        $notif_message_count = $notif->getMessageCount();
        $message_count = $chat->getMessagesCount();
        $messageId = $_GET['messageId'];
        $message = $messagesrepo->findById($messageId)[0];
        $em->remove($message);
        $chat->setMessagesCount($message_count - 1);
        $notif->setMessageCount($notif_message_count - 1);
        $em->flush();
        return $this->redirectToRoute('messages.index', [
            'chatId' => $chatId,
        ]);
    }

}
