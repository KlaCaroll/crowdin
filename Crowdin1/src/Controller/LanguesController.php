<?php

namespace App\Controller;

use App\Entity\Langues;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\LanguesType;
use Doctrine\ORM\EntityManagerInterface;

class LanguesController extends AbstractController
{
    #[Route('/langues/create', name: 'langues.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $langue = new Langues();
        $user = $this->getUser();
        $form = $this->createForm(LanguesType::class, $langue);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $langue->setUser($user);
            $em->persist($langue);
            $em->flush();
            return $this->redirectToRoute('profil.index');
        }
        return $this->render('langues/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/langue/{id}', name: 'langue.delete')]
    public function remove(Langues $langue, EntityManagerInterface $em): Response
    {
        $em->remove($langue);
        $em->flush();
        return $this->redirectToRoute('profil.index');
    }

    #[Route('/langues/{id}/edit', name: 'langue.edit')]
    public function edit(Langues $langue, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LanguesType::class, $langue);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($langue);
            $em->flush();
            return $this->redirectToRoute('profil.index');
        }
        return $this->render('langues/edit.html.twig', [
            'form' => $form
        ]);
    }
}
