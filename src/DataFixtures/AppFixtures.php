<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\CategoryProduct;
use App\Entity\Picture;
use App\Entity\SubCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private SluggerInterface $slugger;
    private $client;

    const  CATEGORIESPROD = [ 'Livre','Ebook','Goodies'];
    const CATEGORIES = ['Mes livres', 'Vos avis', 'Rencontres & Dédidaces', 'Coin partage',
        'Retours des lecteurs', 'Posts passions', 'Ecriture AE'];



    public function __construct(SluggerInterface $slugger, HttpClientInterface $client )
    {
        $this->slugger = $slugger;
        $this->faker = Factory::create('fr_FR');
        $this->client = $client;
    }
    public function getPosts(): array
        {
            $responses = $this->client->request(
                'GET',
                'https://www.lyviapalay-books.fr/wp-json/wp/v2/posts/', [
                    'query' => [
                        'per_page' => 100,
                        'orderby' => 'id', // Trie par ID croissant
                        'order' => 'asc'

                    ],
                ]
            );
            return $responses->toArray();
        }
    public function getPictures(): array
    {
        $allPictures = [];
        for ($i=1;$i<3;$i++){
            $responsesPict = $this->client->request(
                'GET',
                'https://www.lyviapalay-books.fr/wp-json/wp/v2/media/', [
                    'query' => [
                        'per_page' => 100,
                        'page' => $i,
                        'orderby' => 'id', // Trie par ID croissant
                        'order' => 'asc'
                    ],
                ]
            );
            $allPictures[] = $responsesPict->toArray();
        }
          return $allPictures;
    }

    public function load(ObjectManager $manager): void
    {




        /*User role user*/
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($this->faker->firstName);
            $user->setLastname($this->faker->lastName);
            $user->setPlainPassword('123456');
            $user->setIsVerified(false);

            $manager->persist($user);
        }

            $user = new User();
            $user->setEmail('admin@lyviapalay-books.fr');
            $user->setFirstname('lyvia');
            $user->setLastname('palay');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPlainPassword('123456');
            $user->setIsVerified(true);
            $this->addReference('user_admin', $user);
            $manager->persist($user);


        $manager->flush();

        foreach (self::CATEGORIESPROD as $categoryName) {
            $category = new CategoryProduct();
            $category->setName($categoryName);
            $category->setSlug(strtolower($this->slugger->slug($categoryName)));
            $manager->persist($category);
            $this->addReference('categoryProd_' . $categoryName, $category);
        }
        $manager->flush();

        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setSlug(strtolower($this->slugger->slug($categoryName)));
            $this->addReference('category_' . $categoryName, $category);

            $manager->persist($category);
            $category->setCategory($this->getReference('category_' . $categoryName));
            $manager->persist($category);

        }
        $manager->flush();


        $posts = $this->getPosts();
        $allPictures = $this->getPictures();

        foreach ($posts as $post) {
            $idPost = $post['featured_media'];

            $blog = new Blog();
            $blog->setTitle($post['title']['rendered'])
                ->setSlug($post['slug'])
                ->setContent($post['content']['rendered'])
                ->setExcerpt($post['excerpt']['rendered'])
                ->setCreatedAt(new \DateTime($post['date']))
                ->setUpdatedAt(new \DateTime($post['modified']))
                ->setPublish(true);

            $authorUser = $this->getReference('user_admin');
            $blog->setAuthor($this->getReference('user_admin'))
                ->setCategory($this->getReference('category_' . $this->faker->randomElement(self::CATEGORIES)));
            $manager->persist($blog);
            $this->addReference('post_' . $idPost, $blog);

            foreach ($allPictures as $pagePictures) {
                foreach ($pagePictures as $picture) {
                    if ($picture['id'] === $post['featured_media']) {
                        $newPicture = new Picture();
                        $fullUrl = $picture['source_url'];
                        $baseUrl = "https://www.lyviapalay-books.fr/wp-content/uploads/";
                        $relativeUrl = str_replace($baseUrl, "", $fullUrl);

                        $newPicture->setUrlName($relativeUrl);
                        $newPicture->setBlog($blog);
                        $manager->persist($newPicture);
                    }
                }
            }
        }

        $manager->flush();
    }

}
