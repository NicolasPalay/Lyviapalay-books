<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryProduct;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private SluggerInterface $slugger;

    const  CATEGORIESPROD = [ 'Livre','Ebook','Goodies'];
const CATEGORIES = ['Côté écriture', 'Côté lecteurs', 'Côté lecture', 'Côté partage'];
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->faker = Factory::create('fr_FR');
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
            $this->addReference('categoryProd_' . $categoryName, $category);
        }
        $manager->flush();
        }



}
