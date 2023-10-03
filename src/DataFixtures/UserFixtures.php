<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
    ){}

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setName('test');
        $user->setEmail('test@test.test');
        $user->setPassword(
            $this->passwordEncoder->hashPassword($user, 'Azqswx8!')
        );
        $manager->persist($user);

        for($usr = 1; $usr<=2; $usr++){
            $user = new User();
            $user->setName($faker->name);
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'Azqswx8!')
            );
            $manager->persist($user);
        }

        $manager->flush();
    }
}
