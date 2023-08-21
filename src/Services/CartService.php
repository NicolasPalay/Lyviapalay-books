<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    public function getFullCart(SessionInterface  $session, ProductRepository
                                                  $productRepository): array
    {
        $panier = $session->get('panier', []);
        $data = [];
        $total = 0;
        foreach ($panier as $id => $quantity) {
            $product = $productRepository->find($id);
            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        return ['data' => $data, 'total' => $total];

    }
}