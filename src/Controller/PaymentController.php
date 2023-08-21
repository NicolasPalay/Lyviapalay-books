<?php

namespace App\Controller;


use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;

class PaymentController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('compte/payment/{reference}', name: 'payment_index', methods: ['GET'])]
    public function stripeCheckout($reference): RedirectResponse
    {
    $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' =>$reference]);
    $productsStripe = [];

        if(!$order){
            return $this->redirectToRoute('cart_index');
        }
        foreach ($order->getOrderDetails()->getValues() as $product){
            $productsStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($product->getProduct()->getPrice()*100),
                    'product_data' => [
                        'name' => $product->getProduct()->getName(),
                                        ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
        $carrierStripe = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => (int) ($order->getCarrierPrice() * 100), // Convert to cents
                'product_data' => [
                    'name' => $order->getCarrierName(),
                ],
            ],
            'quantity' => 1,
        ];

        $productsStripe[] = $carrierStripe;


       Stripe::setApiKey('sk_test_51NfktXDt9N58DMNsDEHDqWKOh4HYrjfXw03RoGVZPh6faT4ktDpDMPZdrHMdK6FtUJ5nsjWJmHRzexczoJjFMNQW00d42rUwzh');
        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
              $productsStripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        return new RedirectResponse($checkout_session->url, 303);
    }

    #[Route('compte/payment/success', name: 'payment_sucess')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }}