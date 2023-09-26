<?php

namespace App\Controller;

use App\Entity\Decication;
use App\Form\DecicationType;
use App\Repository\DecicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/decication')]
class DecicationController extends AbstractController
{
    #[Route('/', name: 'app_decication_index', methods: ['GET'])]
    public function index(DecicationRepository $decicationRepository): Response
    {
        return $this->render('decication/index.html.twig', [
            'decications' => $decicationRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_decication_show', methods: ['GET'])]
    public function show(Decication $decication): Response
    {
        return $this->render('decication/show.html.twig', [
            'decication' => $decication,
        ]);
    }


}
