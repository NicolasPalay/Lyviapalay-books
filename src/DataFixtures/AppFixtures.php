<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
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

            $manager->persist($user);
        }

            $user = new User();
            $user->setEmail('admin@lyviapalay-books.fr');
            $user->setFirstname('lyvia');
            $user->setLastname('palay');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPlainPassword('123456');

            $manager->persist($user);

        $manager->flush();
    }
}
