<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $categories = ["Technology", "Health", "Education","Medicam"];

        foreach ($categories as $category) {
            $category1 = new Category();
            $category1->setName($category);
            $manager->persist($category1);
        }
        $manager->flush();
    }
}