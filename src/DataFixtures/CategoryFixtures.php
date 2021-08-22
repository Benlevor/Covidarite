<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1 -> setTitle("Bénévolat");
        $manager->persist($category1);

        $manager->flush();


        $category2 = new Category();
        $category2 -> setTitle("Demande d'aide");
        $manager->persist($category2);

        $manager->flush();
    }
}
