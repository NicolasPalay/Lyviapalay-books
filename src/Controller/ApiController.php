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
            'https://www.lyviapalay-books.fr/wp-json/wp/v2/users', [
                'query' => [
                    'per_page' => 100,
                    'orderby' => 'id', // Trie par ID croissant
                    'order' => 'asc'

                ],
            ]
        );
        $apis = $responses->toArray();


        $responses2 = $this->client->request(
            'GET',
            'https://www.lyviapalay-books.fr/wp-json/wp/v2/posts/', [
                'query' => [
                    'per_page' => 100,
                    'orderby' => 'id', // Trie par ID croissant
                    'order' => 'asc'

                ],
            ]


        );
        $apis2= $responses2->toArray();

        $asis = array_merge($apis,$apis2);



        dd($apis) ;

        return $this->render('api.html.twig', ['apis' => $apis]);
    }
  /*  public function getPosts(): array
    {
        $consumer_key = 'ck_8d94d3b2cc86d3262bf136146376ea722eda36b8';
        $consumer_secret = 'cs_d7174e218d3703e32655089007c8f61ea759c77e';
        $endpoint = 'products';
        $base_url = 'https://www.lyviapalay-books.fr/wp-json/wc/v3/';
        $url = $base_url . $endpoint;
        $responses = $this->client->request(
            'GET',
        //    'https://www.lyviapalay-books.fr/wp-json/wc/v3/products',
            $url,
            [
                'auth_basic' => [$consumer_key, $consumer_secret],
                'query' => [
                    'per_page' => 20,
                ],
            ]

        );

        $apis = $responses->toArray();


foreach ($apis as $api){
   $image=  $api['images'][0]['src'];
    dd($image) ;
}
        return $this->render('api.html.twig', ['apis' => $apis]);
    }*/
}
