<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountCommande extends AbstractController
{
    #[Route('compte/commande', name: 'account_order')]
    public function index(OrderRepository $orderRepository): Response
    {

        $commandes = $orderRepository->findSuccessOrders($this->getUser());

        return $this->render('account/commande.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}