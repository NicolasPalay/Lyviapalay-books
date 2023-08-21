<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    #[Route('/')]
    public function getPosts(): array
    {
        $responses = $this->client->request(
            'GET',
            'https://www.lyviapalay-books.fr/wp-json/wp/v2/posts/',
            [
                'query' => [
                    'per_page' => 100,
                ],
            ]
        );

        $apis = $responses->toArray();

dd($apis) ;
        return $this->render('api.html.twig', ['apis' => $apis]);
    }
}
