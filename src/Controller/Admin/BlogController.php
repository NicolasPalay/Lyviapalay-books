<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('admin/blog', name: 'admin_blog_')]
class BlogController extends AbstractController
{
    public function __construct(HttpClientInterface $client )
    {

        $this->client = $client;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    { $responses = $this->client->request(
        'GET',
        'https://www.lyviapalay-books.fr/wp-json/wp/v2/posts/'
    );
        $responses->toArray();

        return $this->render('admin/blog/index.html.twig', [
           'posts' => $responses->toArray(),
        ]);
    }


}
