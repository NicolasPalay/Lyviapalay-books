<?php

namespace App\DataFixtures;

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
    const SUBCATS = ['Retour de lecture','Citations', 'Ecriture & Inspiration'];


    public function __construct(SluggerInterface $slugger, HttpClientInterface $client )
    {
        $this->slugger = $slugger;
        $this->faker = Factory::create('fr_FR');
        $this->client = $client;
    }
    public function getPosts(): array
        {
            $response = $this->client->request(
                'GET',
                'https://www.lyviapalay-books.fr/wp-json/wp/v2/posts/'
            );
            return $response->toArray();

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
            $manager->persist($category);
            $this->addReference('category_' . $categoryName, $category);
        }
        $manager->flush();

        foreach (self::SUBCATS as $categoryName) {
        $subCategory = new SubCategory();
            $subCategory->setName($categoryName);
            $subCategory->setCategory($this->getReference('category_' . 'Coin partage'));
            $subCategory->setSlug(strtolower($this->slugger->slug($categoryName)));
        $manager->persist($subCategory);
        $this->addReference('categoryProd_' . $categoryName, $subCategory);
    }
        $manager->flush();
        }



}
