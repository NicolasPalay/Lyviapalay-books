<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    public function getFullCart(SessionInterface $session, ProductRepository $productRepository): array
    {
        $panier = $session->get('panier', []);
        $reduction = $session->get('reduction', []);
        $codeReduction = $reduction[1] ?? null;

        $data = [];
        $total = 0;
        $totalWeight = 0;
        $montantReduct = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productRepository->find($id);
            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];

            $total += $product->getPrice() * $quantity;



            $totalWeight += $quantity * $product->getWeight();
        }
        if (isset($reduction[0]) && $reduction[0] > 0) {
            $montantReduct = $total / 100 * $reduction[0] ;
            $total = $total - $montantReduct;
        }
        return [
            'data' => $data,
            'total' => $total,
            'totalWeight' => $totalWeight,
            'reduction' => $reduction,
            'montantReduct' => $montantReduct,
            'codeReduction' => $codeReduction
        ];
    }
}