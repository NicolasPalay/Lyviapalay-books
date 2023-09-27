<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(SessionInterface $session,$stripeSessionId,): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSession($stripeSessionId);
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_home');
        }
        $session->remove('panier');
        $session->remove('reduction');
if (!$order->isIsPaid()) {
            $order->setIsPaid(true);
            $this->entityManager->flush();
    $adresse = $order->getDelivery();
    $adresse = str_replace('[br]','<br>',$adresse);
    $content = "Bonjour " . $order->getUser()->getFirstname() . ", <br><br> Votre commande n° " .
        $order->getReference() . " est bien validée. <br><br>";

    foreach ($order->getOrderDetails()->getValues() as $product){
        $content .= $product->getProduct()->getName() . ' x ' . $product->getQuantity() . '<br>';
    }
    $content .= "Total de votre commande : " . round($order->getTotalPrice(),2 )+
        round($order->getCarrierPrice(),2) .
        "€ <br><hr>";
    $content .= "<p  style='color: white; font-size: 20px'>Votre commande sera livré à l'adresse suivante : </p><br>";
    $content .= "<span  style='color: white; font-size: 20px'>" . $order->getUser()
            ->getFirstname() . ' ' . $order->getUser()->getLastname() . '</span><br>';
    $content .= "<span  style='color: white; font-size: 20px'>" . $adresse . '</span><br><hr>';
    $content .= "<p  style='color: white; font-size: 20px'>Voici votre démande de décicace :</p>";
    $content .= "<span  style='color: white; font-size: 20px'>" . $order->getdedication() .
        '</span><br><hr>';
    $content .= "<p  style='color: white; font-size: 20px'>Votre commande sera expédié dans les plus brefs délais </p><br><br>";

    $content .= "<p  style='color: white; font-size: 20px'>Merci de votre confiance</p> <br>";
    $mail = new Mail();
    $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(), 'Confirmation de commande de la boutique de Lyvia Palay', $content);

}
        return $this->render('order/order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
