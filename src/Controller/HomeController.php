<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Product;
use App\Form\SearchBLogType;
use App\Model\SearchData;
use App\Repository\BlogRepository;
use App\Repository\CitationRepository;
use App\Repository\DecicationRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BlogRepository $blogRepository,
                          ProductRepository $productRepository,
                          DecicationRepository $dedicationRepository,

    Request $request): Response
    {
      $dedication =  $dedicationRepository->findDecicationNext();
        $direct = 0;
      if($dedication) {
          $direct = $dedication[0];
      }

    return $this->render('home/index.html.twig', [
        'dedication' => $direct,
        'articlesPromoted' => $blogRepository->promoteQuery(),
        'articles' => $blogRepository->findby([],['id' => 'desc'], 6),
        'products'=> $productRepository->findby([],['id' => 'DESC'], 3),
        'citations' => $blogRepository->findby(['category' => 59],['id' => 'DESC'], 6
        ),
    ]);
    }
}
