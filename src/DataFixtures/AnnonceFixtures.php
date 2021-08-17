<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Annonce;

class AnnonceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
           $annonce = new Annonce(); 
           $annonce -> setTitle("Titre de l'annonce n°$i")
                    -> setContent("<p> Contenu de l'annonce n°$i</p>")
                    -> setImage("http://placehold.it/350x150")
                    -> setCreatedAt(new \DateTimeImmutable())
                    -> setType("Type d'utilisateur");
            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
