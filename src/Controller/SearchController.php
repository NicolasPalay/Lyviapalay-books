<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchBLogType;
use App\Model\SearchData;
use App\Repository\BlogRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
#[Route('/recherche', name: 'search')]
    public function searchBar(BlogRepository $blogRepository, Request $request): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchBLogType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchData = $form->getData();

            $searchData->page = $request->query->getInt('page', 1);
            $blogs = $blogRepository->findSearch($searchData);
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide, vous devez retourner une réponse par défaut
        return $this->render('blog/searchBlog.html.twig', [
            'blogs' => $blogRepository->findSearch($searchData),
            'searchResult' => $searchData,
            // Autres données à passer au template par défaut
        ]);
    }
    public function renderSearchForm(): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchBLogType::class, $searchData);

        return $this->render('_includes/_searchData.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
