<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Event;
use App\Entity\User;

class EventFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Obtenez tous les utilisateurs existants depuis la base de données
        $existingUsers = $manager->getRepository(User::class)->findAll();

        // Créez des événements pour chaque utilisateur existant
        foreach ($existingUsers as $user) {
            // Créez 2 événements pour chaque utilisateur
            for ($j = 1; $j <= 2; $j++) {
                $event = new Event();//'Evenement'.$j
                $event->setTitle(implode(' ', $faker->words(5)));
                $event->setDescription(implode(' ', $faker->words(50)));
                $event->setBeginAt($faker->dateTimeBetween('now', '+30 days'));
                $event->setEndAt($faker->dateTimeBetween($event->getBeginAt(), '+60 days'));
                $event->setPlace($faker->address);
                $event->setCreator($user);

                $manager->persist($event);
            }
        }

        $manager->flush();
    }
}
