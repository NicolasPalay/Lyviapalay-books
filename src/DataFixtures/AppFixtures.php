<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\CategoryProduct;
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
    const CATEGORIES = ['Mes livres', 'Vos avis', 'Rencontres & Dédidaces', 'Coin partage'];



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
                    ],
                ]
            );
            return $responses->toArray();
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
        foreach($posts as $post) {
            $blog = new Blog();
            $blog->setTitle($post['title']['rendered'])
                    ->setSlug($post['slug'])
                    ->setContent($post['content']['rendered'])
                    ->setCreatedAt(new \DateTime($post['date']))
                    ->setUpdatedAt(new \DateTime($post['modified']))
            ->setPublish(true);
            $authorUser = $this->getReference('user_admin');
            $blog->setAuthor($authorUser->getId())
                    ->setCategory($this->getReference('category_' . $this->faker->randomElement(self::CATEGORIES)));


            $manager->persist($blog);
            $this->addReference('post_' . $post['title']['rendered'], $blog);
        }
        $manager->flush();

    }
}
