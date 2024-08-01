<?php

namespace App\Controller;

use App\Entity\Sources;
use App\Entity\Projects;
use App\Form\BlockProjectType;
use App\Form\SourcesType;
use App\Form\CsvImportType;
use App\Service\CsvService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SourcesRepository;
use App\Repository\ProjectsRepository;
use App\Repository\UsersRepository;
use App\Repository\TraductionsRepository;


class SourcesController extends AbstractController
{
    #[Route('/sources', name: 'sources.index')]
    public function index(Request $request, SourcesRepository $repository, ProjectsRepository $projectrepo, TraductionsRepository $traductionsrepo, EntityManagerInterface $em): Response
    {
        $projectId = $_GET['projectId'];
        $sources = $repository->findByProjectId($projectId);
        $project = $projectrepo->findProjectById($projectId);

        $form = $this->createForm(BlockProjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('block')->isClicked()) {
                $project->setBloque(true);
            } elseif ($form->get('unblock')->isClicked()) {
                $project->setBloque(false);
            }
            
            $em->persist($project);
            $em->flush();
            return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
        }
        $importForm = $this->createForm(CsvImportType::class, null, [
            'action' => $this->generateUrl('sources.import', ['projectId' => $projectId]),
        ]);

        return $this->render('sources/index.html.twig', [
            'sources' => $sources,
            'projectId' => $projectId,
            'project' => $project,
            'form' => $form->createView(),
            'importForm' => $importForm->createView(),
        ]);
    }

    #[Route('/sourcesvisit', name: 'sources.visit')]
    public function show(Request $request, SourcesRepository $repository, ProjectsRepository $projectrepo, UsersRepository $userrepo): Response
    {
        $projectId = $_GET['projectId'];
        $sources = $repository->findByProjectId($projectId);
        $project = $projectrepo->findProjectById($projectId);

         if ($project->isBloque()) {
            $this->addFlash('error', 'Ce projet est actuellement bloqué.');
            return $this->redirectToRoute('projects.index'); 
        }

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


    private CsvService $csvService;

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
    }
    
    #[Route('/sources/import', name: 'sources.import')]
    public function import(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CsvImportType::class);
        $projectId = $_GET['projectId'];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv_file')->getData();
            if ($csvFile) {
                if (!$projectId) {
                    $this->addFlash('error', 'Project ID is required.');
                    return $this->redirectToRoute('sources.index');
                }

                $mimeType = $csvFile->getMimeType();
                $extension = $csvFile->guessExtension();

                if ($mimeType !== 'text/csv' && $extension !== 'csv') {
                    $this->addFlash('error', 'Invalid file type. Please upload a CSV file.');
                    return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
                }

                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $this->csvService->handleCsvImport($csvFile, (int)$projectId, $em);
                return $this->redirectToRoute('sources.index', ['projectId' => $projectId]);
            }
        }

        return $this->render('sources/index.html.twig', [
            'form' => $form->createView(),
            'projectId' => $projectId,
        ]);
    }

    #[Route('/sources/export', name: 'sources.export')]
    public function export(Request $request, EntityManagerInterface $em, SourcesRepository $repository, ProjectsRepository $projectrepo, TraductionsRepository $traductionsrepo): StreamedResponse
    {
        $projectId = $_GET['projectId'];

        $response = new StreamedResponse(function () use ($repository, $projectId, $traductionsrepo) {
            $handle = fopen('php://output', 'w+');
            
            fputcsv($handle, ['name', 'text', 'lang']);
            
            $sources = $repository->findByProjectId($projectId);
            
            foreach ($sources as $source) {
                $traductions = $traductionsrepo->findBySourceId($source);
                
                foreach ($traductions as $traduction) {
                    fputcsv($handle, [
                        $source->getClef(),
                        $traduction->getContenu(),
                        $traduction->getLangue()
                    ]);
                }
            }
            
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_sources.csv"');

        return $response;
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
