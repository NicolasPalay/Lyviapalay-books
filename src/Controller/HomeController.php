<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\BlogRepository;
use App\Repository\CitationRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BlogRepository $blogRepository,
                          ProductRepository $productRepository,
                          CitationRepository  $citationRepository): Response
    {
    ;
        return $this->render('home/index.html.twig', [
            'articles' => $blogRepository->findby([],['id' => 'desc'], 6),
            'products'=> $productRepository->findby([],['id' => 'DESC'], 3),
            'citations' => $citationRepository->findby([],['id' => 'DESC'], 3),
        ]);
    }
}
