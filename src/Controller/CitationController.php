<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Entity\User;
use App\Form\CitationType;
use App\Repository\CitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/citation')]
class CitationController extends AbstractController
{
    #[Route('/', name: 'app_citation_index', methods: ['GET'])]
    public function index(CitationRepository $citationRepository): Response
    {
        return $this->render('citation/index.html.twig', [
            'citations' => $citationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_citation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {


        $citation = new Citation();


        $form = $this->createForm(CitationType::class, $citation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($citation);
            $entityManager->flush();

            return $this->redirectToRoute('app_citation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('citation/new.html.twig', [
            'citation' => $citation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citation_show', methods: ['GET'])]
    public function show(Citation $citation): Response
    {
        return $this->render('citation/show.html.twig', [
            'citation' => $citation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_citation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Citation $citation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CitationType::class, $citation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_citation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('citation/edit.html.twig', [
            'citation' => $citation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citation_delete', methods: ['POST'])]
    public function delete(Request $request, Citation $citation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$citation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($citation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_citation_index', [], Response::HTTP_SEE_OTHER);
    }
}
