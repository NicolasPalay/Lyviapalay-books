<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/mentions-legales', name: 'mentions_legales')]
    public function mentions(): Response
    {
        return $this->render('pages/mentions_legales.html.twig', [

        ]);
    }
    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('pages/mentions.html.twig', [

        ]);
    }
    #[Route('/biographie', name: 'bio')]
    public function biographie(): Response
    {
        return $this->render('pages/biographie.html.twig', [

        ]);
    }

}
