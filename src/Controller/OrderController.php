<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande', name: 'app_order_')]
class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route ('/', name: 'index')]
    public function index(Request $request,
                          CartService $cart,
                          SessionInterface  $session,
                          ProductRepository $productRepository):    Response
    {
       $data = $cart->getFullCart($session, $productRepository);
       //dd($data);
       if($data['total'] == 0) {
           return $this->redirectToRoute('boutique_index');
       }
        if(!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        $user = $this->getUser();
        $form = $this->createForm(OrderType::class,null,['user' => $user]);




        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'items'=>$data,
        ]);
    }

    #[Route ('/recapitulatif', name: 'recap',methods: ['POST'])]
    public function add(Request $request,
                        CartService $cart,
                        SessionInterface  $session,
                        ProductRepository $productRepository,
                        OrderRepository $orderRepository,
                        OrderDetailRepository $orderDetailRepository):    Response
    {
        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {

            $carriers = $form->get('carriers')->getData();
            $transport= $carriers->getPrice() * $cart->getFullCart($session, $productRepository)['totalWeight'];
            $delivery = $form->get('adresses')->getData();
            $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);
            if ($lastOrder) {
                $lastOrderReference = $lastOrder->getReference();
            } else {
                $lastOrderReference = 1005;
            }

            $order = new Order();
            $order->setUser($this->getUser())

                ->setCarrierName($carriers->getName())
                ->setCarrierPrice($transport)
                ->setDelivery($delivery->getFirstname() . ' ' . $delivery->getLastname() . '[br] tél : '
                    . $delivery->getPhone() . '[br]'
                    . $delivery->getAdress() . '[br]'
                    . $delivery->getPostal() . ' '
                    . $delivery->getCity() . '[br]'
                    . $delivery->getPays())
                ->setIsPaid(0);
            if ($lastOrderReference) {
                $order->setReference($lastOrderReference + 1);
            }
            $this->entityManager->persist($order);
               $this->entityManager->flush();

            foreach ($cart->getFullCart($session, $productRepository)['data'] as $product) {
                $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);

                $orderDetails = new OrderDetail();
                $orderDetails->setReference($lastOrder)
                    ->setProduct($product['product'])
                    ->setQuantity($product['quantity'])
                    ->setPrice($product['product']->getPrice())
                  ->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);


            }
            $this->entityManager->flush();


              $session->remove('panier');
               $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);
               $orderDetails = $orderDetailRepository->findBy(['reference' => $lastOrder], ['id' => 'desc']);
               return $this->render('order/add.html.twig', [
                   'order' => $lastOrder,
                   'orderDetails' => $orderDetails,

               ]);

           }
        return $this->redirectToRoute('app_order_index');

        }

}
