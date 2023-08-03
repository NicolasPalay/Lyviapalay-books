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

    #[Route('/{slug}', name: 'app_citation_show', methods: ['GET'])]
    public function show(Citation $citation): Response
    {
        return $this->render('citation/show.html.twig', [
            'citation' => $citation,
        ]);
    }

}
