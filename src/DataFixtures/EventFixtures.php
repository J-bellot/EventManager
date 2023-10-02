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
                $event = new Event();
                $event->setTitle(implode(' ', $faker->words(5)));
                $event->setDescription(implode(' ', $faker->words(50)));
        
                // Générez des dates aléatoires avec Faker
                $beginAt = $faker->dateTimeBetween('now', '+30 days');
                $endAt = $faker->dateTimeBetween($beginAt, '+60 days');
        
                // Convertissez les objets DateTime en DateTimeImmutable
                $beginAt = \DateTimeImmutable::createFromMutable($beginAt);
                $endAt = \DateTimeImmutable::createFromMutable($endAt);
        
                $event->setBeginAt($beginAt);
                $event->setEndAt($endAt);
        
                $event->setPlace($faker->address);
                $event->setCreator($user);
        
                $manager->persist($event);
            }
        }

        $manager->flush();
    }
}