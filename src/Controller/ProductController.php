<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boutique')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'boutique_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $search = new Search();
        $form= $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $products = $productRepository->findBySearch($search);

        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }


    #[Route('/{slug}', name: 'app_product_show', methods: ['GET'])]
    public function show(ProductRepository $productRepository, $slug): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if (!$product) {
            return $this->redirectToRoute('boutique_index');
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }


}
