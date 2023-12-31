<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\CarrierRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Services\CarrierService;
use App\Services\CartService;
use App\Services\CommentValidator;
use App\Services\Listing;
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
    public function index(Request               $request,
                          CartService           $cart,
                          CarrierService        $carrier,
                          SessionInterface      $session,
                          ProductRepository     $productRepository,
                          OrderRepository       $orderRepository,
                          CarrierRepository     $carrierRepository,
                          OrderDetailRepository $orderDetailRepository,
                          Listing $listing,
                          CommentValidator $commentValidator,

    ): Response
    {
        $data = $cart->getFullCart($session, $productRepository);
        //dd($data);
        if ($data['total'] == 0) {
            return $this->redirectToRoute('boutique_index');
        }
        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }
        $reduction = $data['reduction'];
        $montantReduct = $data['montantReduct'];

        $user = $this->getUser();
        $form = $this->createForm(OrderType::class, null, ['user' => $user]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $content= 'dedication';
            if (!$commentValidator->validateComment($form,$content, $listing)) {
                $this->addFlash('danger', 'Type de commentaire invalide. Veuillez éviter d\'utiliser des mots inappropriés.');
                return $this->redirectToRoute('app_order_index');
            }


            $transport = $carrier->getCarrierPrice($cart, $carrierRepository, $session, $productRepository)->getPrice();

            $delivery = $form->get('adresses')->getData();
            $dedication = $form->get('dedication')->getData();
            $montantReductCart = 0;
            $montantReductCart = $cart->getFullCart($session, $productRepository)['montantReduct'];
            $codeReductCart = $cart->getFullCart($session, $productRepository)['codeReduction'];

            $total = $cart->getFullCart($session, $productRepository)['total'];




            $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);
            if ($lastOrder) {
                $lastOrderReference = $lastOrder->getReference();
            } else {
                $lastOrderReference = 1005;
            }

            $order = new Order();
            $order->setUser($this->getUser())
                ->setCarrierName($carrier->getCarrierPrice($cart, $carrierRepository, $session, $productRepository)->getName())
                ->setCarrierPrice($transport)
                ->setDelivery($delivery->getFirstname() . ' ' . $delivery->getLastname() . '[br] tél : '
                    . $delivery->getPhone() . '[br]'
                    . $delivery->getAdress() . '[br]'
                    . $delivery->getPostal() . ' '
                    . $delivery->getCity() . '[br]'
                    . $delivery->getPays())
                ->setIsPaid(0)
                ->setDedication($dedication)
                ->setReductionPrice($montantReductCart)
                ->setReductionCode($codeReductCart)
                ->setTotalPrice($total);
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
            return $this->redirectToRoute('app_order_recap', ['reference' => $order->getReference()]);
        }
        $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'items' => $data,
            'lastOrder' => $lastOrder,
            'reduction' => $reduction,

            'montantReduct' => $montantReduct,
        ]);
    }

    #[Route ('/recapitulatif/{reference}', name: 'recap', methods: ['GET'])]
    public function add(Request               $request,
                        SessionInterface      $session,
                        OrderRepository       $orderRepository,
                        OrderDetailRepository $orderDetailRepository): Response
    {
        $lastOrder = $orderRepository->findOneBy(['user'=>$this->getUser()], ['id' => 'desc']);
        $reference = $lastOrder->getReference();
        if( $reference != $request->get('reference') && $this->getUser()){
           return $this->redirectToRoute('boutique_index');
        }



        $lastOrder = $orderRepository->findOneBy([], ['id' => 'desc']);
        $orderDetails = $orderDetailRepository->findBy(['reference' => $lastOrder], ['id' => 'desc']);
        return $this->render('order/add.html.twig', [
            'order' => $lastOrder,
            'orderDetails' => $orderDetails,

        ]);
    }

}
