<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{

    /**
     * @param BlogRepository $blogRepository
     * @param Request $request
     * @param PaginatorInterface $paginator KnpPaginator
     * @return Response
     */
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository,Request $request, PaginatorInterface $paginator ):
    Response
    {
        $articles = $blogRepository->findby([],['id' => 'desc']);
        $pagination = $paginator->paginate(
            $blogRepository->paginationQuery(),
            $request->query->getInt('page', 1),12
        );
        return $this->render('blog/index.html.twig', [

            'pagination' => $pagination,
        ]);
    }
    #[Route('/{slug}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

}
