<?php

namespace App\Controller;

use App\Entity\Traductions;
use App\Form\TraductionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TraductionsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\SourcesRepository;
use App\Repository\UsersRepository;

class TraductionsController extends AbstractController
{
    #[Route('/traductions', name: 'traductions.index')]
    public function index(Request $request, TraductionsRepository $traductionsrepo, SourcesRepository $sourcesrepo, ProjectsRepository $projectsrepo): Response
    {
        $user = $this->getUser();
        if ($user) {
            $user_id = $user->getId();
            $projectId = $_GET['projectId'];
            $project = $projectsrepo->findProjectById($projectId);
            $sourceId = $_GET['sourceId'];
            $traductions = $traductionsrepo->findBySourceId($sourceId);
            $source = $sourcesrepo->findById($sourceId);
            return $this->render('traductions/index.html.twig', [
                'traductions' => $traductions,
                'project' => $project,
                'projectId' => $projectId,
                'source' => $source,
                'user_id' => $user_id
        ]);
        } else {
            $projectId = $_GET['projectId'];
            $project = $projectsrepo->findProjectById($projectId);
            $sourceId = $_GET['sourceId'];
            $traductions = $traductionsrepo->findBySourceId($sourceId);
            $source = $sourcesrepo->findById($sourceId);
            $user_id = 0;
            return $this->render('traductions/index.html.twig', [
                'traductions' => $traductions,
                'project' => $project,
                'projectId' => $projectId,
                'source' => $source,
                'user_id' => $user_id
        ]);
        }
    }

    #[Route('/traductions/create', name: 'traductions.create')]
    public function create(Request $request, EntityManagerInterface $em, SourcesRepository $sourcesrepo, ProjectsRepository $projectsrepo): Response
    {
        $sourceId = $_GET['sourceId'];
        $traducteur_id = $this->getUser()->getId();
        $langue = $_GET['langue'];
        $source = $sourcesrepo->findByID($sourceId);
        $projectId = $_GET['projectId'];
        $project = $projectsrepo->findProjectById($projectId);
        $traduction = new Traductions();
        $form = $this->createForm(TraductionsType::class, $traduction);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $traduction->setSource($source);
            $traduction->setLangue($langue);
            $traduction->setCreatedAt(new \DateTimeImmutable());
            $traduction->setTraducteur($traducteur_id);
            $em->persist($traduction);
            $em->flush();
            return $this->redirectToRoute('traductions.index', [
                'projectId' => $projectId, 
                'sourceId' => $sourceId,
            ]);
        }
        return $this->render('traductions/create.html.twig', [
            'form' => $form,
            'source' => $source,
            'projectId' => $projectId, 
            'sourceId' => $sourceId,
            'project' => $project,
            'langue' => $langue
        ]);
    }

    #[Route('/traductions/{id}', name: 'traductions.delete')]
    public function remove(Traductions $traduction, EntityManagerInterface $em): Response
    {
        $projectId = $_GET['projectId'];
        $sourceId = $_GET['sourceId'];
        $em->remove($traduction);
        $em->flush();
        return $this->redirectToRoute('traductions.index', [
            'projectId' => $projectId,
            'sourceId' => $sourceId
        ]);
    }
}
