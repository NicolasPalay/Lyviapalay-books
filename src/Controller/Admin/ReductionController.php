<?php

namespace App\Controller\Admin;

use App\Entity\Reduction;
use App\Form\ReductionType;
use App\Repository\ReductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('admin/reduction')]
class ReductionController extends AbstractController
{
    #[Route('/', name: 'admin_reduction_index', methods: ['GET'])]
    public function index(ReductionRepository $reductionRepository): Response
    {
        return $this->render('admin/reduction/index.html.twig', [
            'reductions' => $reductionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_reduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reduction = new Reduction();
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reduction);
            $entityManager->flush();

            return $this->redirectToRoute('admin_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/reduction/new.html.twig', [
            'reduction' => $reduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_reduction_show', methods: ['GET'])]
    public function show(Reduction $reduction): Response
    {
        return $this->render('admin/reduction/show.html.twig', [
            'reduction' => $reduction,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_reduction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reduction $reduction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/reduction/edit.html.twig', [
            'reduction' => $reduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_reduction_delete', methods: ['POST'])]
    public function delete(Request $request, Reduction $reduction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reduction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reduction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_reduction_index', [], Response::HTTP_SEE_OTHER);
    }
}
