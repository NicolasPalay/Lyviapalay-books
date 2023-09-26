<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\CategoryProduct;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchType;
use App\Repository\CategoryProductRepository;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use App\Services\CommentValidator;
use App\Services\Listing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'boutique_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository, CategoryProductRepository
    $categoryProduct): Response
    {
        $products = $productRepository->findby([], ['id' => 'DESC']);



        return $this->render('product/index.html.twig', [
            'products' => $products,
            'catProducts' => $categoryProduct->findAll()

        ]);
    }


    #[Route('/{slug}', name: 'app_product_show', methods: ['GET','POST'])]
    public function show(ProductRepository $productRepository, $slug,
                         CommentRepository $comment,Request $request,EntityManagerInterface
                                           $entityManager, Listing $listing, CommentValidator
                         $commentValidator): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if (!$product) {
            return $this->redirectToRoute('boutique_index');
        }
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $content= 'content';
            if (!$commentValidator->validateComment($form,$content, $listing)) {
                $this->addFlash('danger', 'Type de commentaire invalide. Veuillez éviter d\'utiliser des mots inappropriés.');
                return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug()]);
            }


            $newComment->setProduct($product);
            $newComment->setUser($this->getUser());

            $entityManager->persist($newComment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire a bien été envoyé. Il sera publié après validation.');
            return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug()]);
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'comments' => $comment->findByActiveProduct(true,$product, ['id' => 'DESC'])
        ]);
    }


}
