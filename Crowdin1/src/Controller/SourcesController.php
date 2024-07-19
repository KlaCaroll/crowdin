<?php

namespace App\Controller;

use App\Entity\Sources;
use App\Entity\Projects;
use App\Form\SourcesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SourcesRepository;
use App\Repository\ProjectsRepository;
use App\Repository\UsersRepository;
use App\Repository\TraductionsRepository;

class SourcesController extends AbstractController
{
    #[Route('/sources', name: 'sources.index')]
    public function index(Request $request, SourcesRepository $repository, ProjectsRepository $projectrepo, TraductionsRepository $traductionsrepo): Response
    {
        $projectId = $_GET['projectId'];
        $sources = $repository->findByProjectId($projectId);
        $project = $projectrepo->findProjectById($projectId);
        return $this->render('sources/index.html.twig', [
            'sources' => $sources,
            'projectId' => $projectId,
            'project' => $project
        ]);
    }

    #[Route('/sourcesvisit', name: 'sources.visit')]
    public function show(Request $request, SourcesRepository $repository, ProjectsRepository $projectrepo, UsersRepository $userrepo): Response
    {
        $projectId = $_GET['projectId'];
        $sources = $repository->findByProjectId($projectId);
        $project = $projectrepo->findProjectById($projectId);
        $user_id = $project->getUser();
        $user_connected = $this->getUser();
        $user = $userrepo->findUserById($user_id);
        return $this->render('sources/visit.html.twig', [
            'sources' => $sources,
            'project' => $project,
            'user' => $user[0],
            'user_connected' => $user_connected
        ]);
    }

    #[Route('/sources/create', name: 'sources.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $source = new Sources();
        $projectId = $_GET['projectId'];
        $form = $this->createForm(SourcesType::class, $source);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $source->setProjectId($projectId);
            $source->setCreatedAt(new \DateTimeImmutable());
            $source->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($source);
            $em->flush();
            return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
        }
        return $this->render('sources/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/sources/{id}/edit', name: 'sources.edit')]
    public function edit(Request $request, Sources $source, EntityManagerInterface $em): Response
    {
        $projectId = $_GET['projectId'];
        $form = $this->createForm(SourcesType::class, $source);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $source->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($source);
            $em->flush();
            return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
        }
        return $this->render('sources/edit.html.twig', [
            'source' => $source,
            'form' => $form
        ]);
    }

    #[Route('/sources/{id}/delete', name: 'sources.predelete')]
    public function preremove(Sources $source, EntityManagerInterface $em, SourcesRepository $repository, TraductionsRepository $traductionsrepo): Response
    {
        $projectId = $_GET['projectId'];
        $sourceId = $source->getId();
        $traductions = $traductionsrepo->findBySourceId($sourceId);
        // dd($traductions);
        return $this->render('sources/predelete.html.twig', [
            'projectId' => $projectId,
            'sourceId' => $sourceId,
            'traductions' => $traductions,
            'source' => $source
        ]);
    }

    #[Route('/sources/{id}', name: 'sources.delete')]
    public function remove(Sources $source, EntityManagerInterface $em): Response
    {
        $projectId = $_GET['projectId'];
        $em->remove($source);
        $em->flush();
        return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
    }


    // #[Route('/sourcesvisit/{id}/edit', name: 'sourcesvisit.edit')]
    // public function editvisit(Request $request, Sources $source, EntityManagerInterface $em): Response
    // {
    //     $projectId = $_GET['projectId'];
    //     $form = $this->createForm(SourcesType::class, $source);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted()) {
    //         $em->persist($source);
    //         $em->flush();
    //         return $this->redirectToRoute('sources.visit', ['projectId' => $projectId]);
    //     }
    //     return $this->render('sources/visitedit.html.twig', [
    //         'source' => $source,
    //         'form' => $form
    //     ]);
    // }
}
