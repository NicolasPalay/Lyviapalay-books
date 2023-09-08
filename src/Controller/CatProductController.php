<?php

namespace App\Controller;

use App\Entity\CategoryProduct;
use App\Repository\CategoryProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CatProductController extends AbstractController
{
    #[Route('boutique/cat-boutique/{slug}', name: 'app_cat_product')]
    public function index(CategoryProductRepository $categoryProductRepository,$slug,
                          CategoryProduct $categoryProduct): Response
    {
        $catProductMenu = $categoryProductRepository->findAll();

        $categoryProduct = $categoryProductRepository->findOneBy(['slug'=>$slug]);
        $products = $categoryProduct->getProducts();
        return $this->render('cat_product/show.html.twig', [
            'category' => $categoryProduct,
            'products' => $products,
            'catProductMenu' => $catProductMenu
        ]);
    }
}
