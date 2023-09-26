<?php

namespace App\Controller\Admin;

use App\Entity\Decication;
use App\Form\DecicationType;
use App\Repository\DecicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('admin/decication')]
class DecicationController extends AbstractController
{
    #[Route('/', name: 'admin_decication_index', methods: ['GET'])]
    public function index(DecicationRepository $decicationRepository): Response
    {
        return $this->render('admin/decication/index.html.twig', [
            'decications' => $decicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_decication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $decication = new Decication();
        $form = $this->createForm(DecicationType::class, $decication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($decication);
            $entityManager->flush();

            return $this->redirectToRoute('admin_decication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/decication/new.html.twig', [
            'decication' => $decication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_decication_show', methods: ['GET'])]
    public function show(Decication $decication): Response
    {
        return $this->render('admin/decication/show.html.twig', [
            'decication' => $decication,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_decication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Decication $decication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DecicationType::class, $decication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_decication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/decication/edit.html.twig', [
            'decication' => $decication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_decication_delete', methods: ['POST'])]
    public function delete(Request $request, Decication $decication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$decication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($decication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_decication_index', [], Response::HTTP_SEE_OTHER);
    }
}
