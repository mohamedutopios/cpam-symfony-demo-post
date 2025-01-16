<?php

namespace App\DataFixtures;
use App\Entity\Formation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FormationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Générer 10 formations avec des participants aléatoires
        for ($i = 1; $i <= 10; $i++) {
            $formation = new Formation();
            $formation->setTitle('Formation ' . $i);
            $formation->setParticipants(rand(10, 100));

            $manager->persist($formation);
        }

        $manager->flush();
    }
}
