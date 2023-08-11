<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/commande', name: 'app_order_')]
class OrderController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function index(SessionInterface $session, ProductRepository $productRepository,
                          EntityManagerInterface $entityManager, OrderRepository $orderRepository):
    Response
    {

        $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);
        if ($lastOrder) {
        $lastOrderReference = $lastOrder->getReference();
        } else {
            $lastOrderReference = 1005;
        }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $panier = $session->get('panier', []);
        if ($panier === []) {
            $this->addFlash('danger', 'Votre panier est vide');
            return $this->redirectToRoute('boutique_index');
        }
        $order = new Order();
        $order->setUser($this->getUser());

        if ($lastOrderReference) {
            $order->setReference($lastOrderReference + 1);
        }
        foreach ($panier as $item => $quantity) {
            $orderDetail = new OrderDetail();
            $product= $productRepository->find($item);
                $price = $product->getPrice();
                $orderDetail->setProduct($product)
                    ->setQuantity($quantity)
                    ->setPrice($price);

            $order->addOrderDetail($orderDetail);
        }
        $entityManager->persist($order);
        $entityManager->flush();
        $session->remove('panier');
        $this->addFlash('success', 'Votre commande a été enregistrée');
        


        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
