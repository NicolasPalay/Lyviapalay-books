<?php

namespace App\Controller\Admin;

use App\Entity\Citation;
use App\Entity\User;
use App\Form\CitationType;
use App\Repository\CitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/citation', name: 'admin_citation_')]
class CitationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CitationRepository $citationRepository): Response
    {
        return $this->render('admin/citation/index.html.twig', [
            'citations' => $citationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger
    ): Response
    {
        $citation = new Citation();

        $form = $this->createForm(CitationType::class, $citation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($citation->getAuthor() . '-' . rand(0, 100000));
            $citation->setSlug($slug);
            $entityManager->persist($citation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_citation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/citation/new.html.twig', [
            'citation' => $citation,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Citation $citation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CitationType::class, $citation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_citation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/citation/edit.html.twig', [
            'citation' => $citation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Citation $citation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$citation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($citation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_citation_index', [], Response::HTTP_SEE_OTHER);
    }
}
