<?php

namespace App\Controller\Admin;

use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(CommentRepository $commentRepository, BlogRepository $blogRepository, OrderRepository $orderRepository):
    Response
    {
        $orders = $orderRepository->findBy([], ['id' => 'DESC']);
        $comments = $commentRepository->findBy(['active'=>false], ['id' => 'DESC']);
        $articles = $blogRepository->findBy(['publish' =>0],['id' => 'DESC']);
        return $this->render('admin/index.html.twig', [
            'comments' => $comments,
            'articles' => $articles,
            'orders' => $orders
        ]);
    }
}
