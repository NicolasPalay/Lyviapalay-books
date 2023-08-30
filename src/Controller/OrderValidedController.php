<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidedController extends AbstractController
{
    #[Route('/commande/merci/{stripeSession}', name: 'order_valided')]
    public function index($stripeSession,OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findByStripeSession($stripeSession);
        if(!$order || $order[0]->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_home');
        }
        $adresse = $order[0]->getDelivery();
        $adresse = str_replace('[br]','<br>',$adresse);

        $content = "Bonjour " . $order[0]->getUser()->getFirstname() . ", <br><br> Votre commande n° " .
            $order[0]->getReference() . " est bien validée. <br><br>";

            foreach ($order[0]->getOrderDetails()->getValues() as $product){
                $content .= $product->getProduct()->getName() . ' x ' . $product->getQuantity() . '<br>';
            }
        $content .= "Total de votre commande : " . round($order[0]->getTotalPrice(),2 )+
            round($order[0]->getCarrierPrice(),2) .
            "€ <br><hr>";
        $content .= "<p  style='color: white; font-size: 20px'>Votre commande sera livré à l'adresse suivante : </p><br>";
        $content .= "<span  style='color: white; font-size: 20px'>" . $order[0]->getUser()
                ->getFirstname() . ' ' . $order[0]->getUser()->getLastname() . '</span><br>';
        $content .= "<span  style='color: white; font-size: 20px'>" . $adresse . '</span><br><hr>';
        $content .= "<p  style='color: white; font-size: 20px'>Voici votre démande de décicace :</p>";
        $content .= "<span  style='color: white; font-size: 20px'>" . $order[0]->getdedication() .
            '</span><br><hr>';
        $content .= "<p  style='color: white; font-size: 20px'>Votre commande sera expédié dans les plus bref délais </p><br><br>";

        $content .= "<p  style='color: white; font-size: 20px'>Merci de votre confiance</p> <br>";
        $mail = new Mail();
        $mail->send($order[0]->getUser()->getEmail(),$order[0]->getUser()->getFirstname(), 'Confirmation de commande de la boutique de Lyvia Palay', $content);



        return $this->render('order/success.html.twig', [

            'order' => $order[0],
        ]);
    }
}
