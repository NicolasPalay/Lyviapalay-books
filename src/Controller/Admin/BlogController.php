<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Picture;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Services\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('admin/blog', name: 'admin_blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        PictureService $pictureService,
                        SluggerInterface $slugger): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('pictures')->getData();

            $folder = 'pictures';

            foreach ($pictures as $picture) {
                $pictureName = $pictureService->add($picture, $folder, 250, 250);

                $newPicture = new Picture();
                $newPicture->setUrlName($pictureName);
                $blog->addPicture($newPicture);
            }
            $slug = $slugger->slug($blog->getTitle());
            $slug = strtolower($slug);
            $blog->setSlug($slug);
            $blog->setAuthor($this->getUser());
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog,
                         EntityManagerInterface $entityManager,
                         PictureService $pictureService,
                         SluggerInterface $slugger):
    Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('pictures')->getData();

            $folder = 'pictures';

            foreach ($pictures as $picture) {
                $pictureName = $pictureService->add($picture, $folder, 250, 250);

                $newPicture = new Picture();
                $newPicture->setUrlName($pictureName);
                $blog->addPicture($newPicture);
            }
            $slug = $slugger->slug($blog->getTitle());
            $slug = strtolower($slug);
            $blog->setSlug($slug);

            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            foreach ($blog->getPicture() as $picture) {
                $picture->setBlog(null);
            }
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
