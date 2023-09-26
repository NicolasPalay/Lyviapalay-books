<?php

namespace App\Controller\Admin;

use App\Entity\CategoryProduct;
use App\Form\CategoryProductType;
use App\Repository\CategoryProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('admin/category/product', name: 'admin_category_product_')]
class CategoryProductController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CategoryProductRepository $categoryProductRepository): Response
    {
        return $this->render('admin/category_product/index.html.twig', [
            'category_products' => $categoryProductRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface
    $slugger): Response
    {
        $categoryProduct = new CategoryProduct();

        $form = $this->createForm(CategoryProductType::class, $categoryProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($categoryProduct ->getName());
            $categoryProduct->setSlug(strtolower($slug));
            $entityManager->persist($categoryProduct);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category_product_index', [],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_product/new.html.twig', [
            'category_product' => $categoryProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CategoryProduct $categoryProduct): Response
    {
        return $this->render('admin/category_product/show.html.twig', [
            'category_product' => $categoryProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryProduct $categoryProduct,
                         SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryProductType::class, $categoryProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($categoryProduct ->getName());
            $categoryProduct->setSlug(strtolower($slug));
            $entityManager->flush();

            return $this->redirectToRoute('admin_category_product_index', [],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_product/edit.html.twig', [
            'category_product' => $categoryProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, CategoryProduct $categoryProduct, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryProduct->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoryProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_category_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
