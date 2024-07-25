<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProjectsType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProjectsRepository;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'projects.index')]
    public function index(ProjectsRepository $repository): Response
    {
        $projects = $repository->findAll();
        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/my_projects', name: 'projects.show')]
    public function show(ProjectsRepository $repository): Response
    {
        $user = $this->getUser();
        $projects = $repository->findByUser($user);
        return $this->render('projects/show.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projects/create', name: 'projects.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Projects();
        $user = $this->getUser();
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $project->setUser($user);
            $project->setCreatedAt(new \DateTimeImmutable());
            $project->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($project);
            $em->flush();
            $projectId = $project->getId();
            return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
        }
        return $this->render('projects/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/my_projects/{id}/edit', name: 'projects.edit')]
    public function edit(Request $request, Projects $project, ProjectsRepository $repository, EntityManagerInterface $em): Response
    {
        $user_id = $this->getUser()->getId();
        $projectId = $project->getId();
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $project->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($project);
            $em->flush();
            return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
        }
        return $this->render('projects/edit.html.twig', [
            'project' => $project,
            'form' => $form
        ]);
    }

    #[Route('/projects/{id}', name: 'project.delete')]
    public function remove(Projects $project, EntityManagerInterface $em): Response
    {
        $em->remove($project);
        $em->flush();
        return $this->redirectToRoute('projects.show');
    }
}
