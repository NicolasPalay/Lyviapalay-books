<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository
    $productRepository)
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

        return $this->render('cart/index.html.twig', [
            'items' => $data,
            'total' => $total
        ]);
    }

    #[Route('/ajouter/{id}', name: 'ajouter')]
    public function ajouter(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $panier = $session->get('panier', []);
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {

            $panier[$id]++;
        }
       $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function romove(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }

        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {

                unset($panier[$id]);


        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }
    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {

        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
}
