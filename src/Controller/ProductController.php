<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchType;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use App\Services\Listing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boutique')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'boutique_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findby([], ['id' => 'DESC']);
       /* $searchProduct = new Search();
        $form= $this->createForm(SearchType::class, $searchProduct);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $products = $productRepository->findBySearchProduct($searchProduct);

        }*/

        return $this->render('product/index.html.twig', [
            'products' => $products,
           // 'form' => $form->createView()
        ]);
    }


    #[Route('/{slug}', name: 'app_product_show', methods: ['GET','POST'])]
    public function show(ProductRepository $productRepository, $slug,
                         CommentRepository $comment,Request $request,EntityManagerInterface
                                           $entityManager, Listing $listing): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if (!$product) {
            return $this->redirectToRoute('boutique_index');
        }
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData('content');
            $interdit = $listing->getListing();

            $forbidden = str_replace($interdit, '***', $comment->getContent());

            $newComment->setContent($forbidden);
            $newComment->setProduct($product);
            $newComment->setUser($this->getUser());

            $entityManager->persist($newComment);
            $entityManager->flush();
            return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug()]);
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'comments' => $comment->findByActiveProduct(true,$product, ['id' => 'DESC'])
        ]);
    }


}
