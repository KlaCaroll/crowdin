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
use App\Repository\ProjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfilType;

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
        // $projectId = $_GET['projectId'];
        // $project = $projectrepo->findProjectById($projectId);
        $langues = $languesrepo->findByUser($user_id);
        $user = $userrepo->findUserById($user_id);
        return $this->render('profil/visit.html.twig', [
            'user' => $user[0],
            'langues' => $langues,
            // 'project' => $project
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
}
