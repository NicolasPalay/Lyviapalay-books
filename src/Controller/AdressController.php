<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdressController extends AbstractController
{
    #[Route('compte/adresses', name: 'accompte_adress')]
    public function index(): Response
    {

        return $this->render('account/adress/index.html.twig', [

        ]);
    }

    #[Route('compte/ajouter-une-adresse', name: 'accompte_adress_add')]
public function add(): Response
    {
        return $this->render('account/adress/add.html.twig', [

        ]);
    }
}
