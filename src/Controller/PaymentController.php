<?php

namespace App\Controller;


use App\Controller\Secret;
use App\Entity\Order;
use App\Entity\Reduction;
use App\Repository\OrderRepository;
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
    public function stripeCheckout($reference, Secret $secret): RedirectResponse
    {
    $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' =>$reference]);
    $productsStripe = [];
        $couponId = null;
    $reductionCode = $order->getReductionCode();
    if ($reductionCode != null){
    $couponId = $this->entityManager->getRepository(Reduction::class)->findOneBy(['reductCode'
        =>$reductionCode])->getCouponId();
    }
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

$carrier = (int) ($order->getCarrierPrice() * 100);

       // $stripe = new \Stripe\StripeClient
        //('sk_test_51NfktXDt9N58DMNsDEHDqWKOh4HYrjfXw03RoGVZPh6faT4ktDpDMPZdrHMdK6FtUJ5nsjWJmHRzexczoJjFMNQW00d42rUwzh');
       Stripe::setApiKey($secret->secretKey());
        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                $productsStripe,
            ]],
            'discounts' => [
                [
                    'coupon' => $couponId,
                ]
            ],
            'shipping_options' => [
                [
                    'shipping_rate_data' => [
                        'type' => 'fixed_amount',
                        'fixed_amount' => [
                            'amount' => $carrier,
                            'currency' => 'eur',
                        ],
                        'display_name' => $order->getCarrierName(),

                    ],
                ],
            ],

            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSession($checkout_session->id);

        $this->entityManager->flush();
        return new RedirectResponse($checkout_session->url, 303);
    }

}