<?php

namespace App\Services;

use App\Entity\Carrier;
use App\Repository\CarrierRepository;
use App\Repository\ProductRepository;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CarrierService
{
    public function getCarrierPrice(CartService $cart, CarrierRepository $carrierRepository,
                                  SessionInterface  $session, ProductRepository
                                                                                                           $productRepository) : Carrier
    {
        $transport=[];
        $weight = $cart->getFullCart($session, $productRepository)['totalWeight'];
        $carriers = $carrierRepository->findAll();
        if ($weight > 0 && $weight < 0.02) {
            $transport = $carriers[0];
        }elseif($weight > 0.1 && $weight< 0.25){
            $transport = $carriers[1];
        }elseif($weight > 0.1 && $weight< 0.25){
            $transport = $carriers[2];
        }elseif($weight > 0.25 && $weight< 0.50){
            $transport = $carriers[3];
        }elseif($weight > 0.50 && $weight< 0.75){
            $transport = $carriers[4];
        }elseif($weight > 0.75 && $weight< 2){
            $transport = $carriers[5];
        }
        return $transport;
    }
}