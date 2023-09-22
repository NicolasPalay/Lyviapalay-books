<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Form\BlogType;
use App\Form\CommentType;
use App\Form\SearchBLogType;
use App\Model\SearchData;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Services\CommentValidator;
use App\Services\Listing;
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
    #[Route('/{slug}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function show(Blog $blog,CommentRepository $comment,Request $request,EntityManagerInterface
    $entityManager, Listing $listing, CommentValidator
    $commentValidator):
    Response
    {
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
         $content= 'content';
         if (!$commentValidator->validateComment($form,$content, $listing)) {
             $this->addFlash('danger', 'Type de commentaire invalide. Veuillez éviter d\'utiliser des mots inappropriés.');
             return $this->redirectToRoute('app_blog_show', ['slug' => $blog->getSlug()]);
         }
            $newComment->setBlog($blog);
            $newComment->setUser($this->getUser());

            $entityManager->persist($newComment);
            $entityManager->flush();
            return $this->redirectToRoute('app_blog_show', ['slug' => $blog->getSlug()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
          'form' => $form->createView(),
            'comments' => $comment->findByActiveBlog(true,$blog, ['id' => 'DESC'])


        ]);
    }


}
