<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Langues;
use App\Entity\Users;
use App\Repository\LanguesRepository;
use App\Repository\UsersRepository;
use App\Repository\ChatsRepository;
use App\Repository\ProjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfilType;
use App\Form\ChatsType;
use App\Entity\Chats;
use App\Entity\Notifications;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil.index')]
    public function index(LanguesRepository $languesrepo): Response
    {
        $user = $this->getUser();
        $langues = $languesrepo->findByUser($user);
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'langues' => $langues
        ]);
    }

    #[Route('/profilvisit', name: 'profil.visit')]
    public function visit(UsersRepository $userrepo, LanguesRepository $languesrepo, ProjectsRepository $projectrepo): Response
    {
        $user_id = $_GET['userId'];
        $langues = $languesrepo->findByUser($user_id);
        $user = $userrepo->findUserById($user_id);
        return $this->render('profil/visit.html.twig', [
            'user' => $user[0],
            'langues' => $langues,
        ]);
    }

    #[Route('/profil/edit', name: 'profil.edit')]
    public function edit(Users $user, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em->flush();
            return $this->redirectToRoute('profil.index');
        }
        return $this->render('profil/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/chat', name: 'chat.create')]
    public function chat(Request $request, EntityManagerInterface $em, ChatsRepository $chatrepo, UsersRepository $userrepo): Response
    {
        $chat = new Chats();
        $notif_user_connected = new Notifications();
        $notif_user2 = new Notifications();
        $user_connected_id = $this->getUser()->getId();
        $user2_id = $_GET['userId'];
        $user_connected = $userrepo->findUserById($user_connected_id);
        $user2 = $userrepo->findUserById($user2_id);
        $form = $this->createForm(ChatsType::class, $chat);
        $form->handleRequest($request);
        $chat->setUser1($user_connected_id);
        $chat->setUser2($user2_id);
        $chat->setMessagesCount(0);
        $em->persist($chat);
        $em->flush();
        $chatId = $chat->getId();
        $notif_user_connected->setChatId($chatId);
        $notif_user2->setChatId($chatId);
        $notif_user_connected->setAuthorId($user_connected_id);
        $notif_user2->setAuthorId($user2_id);
        $notif_user_connected->setMessageCount(0);
        $notif_user2->setMessageCount(0);
        $em->persist($notif_user_connected);
        $em->persist($notif_user2);
        $em->flush();
        return $this->redirectToRoute('messages.index', ['chatId' => $chatId]);
    }

}
