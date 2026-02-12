<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Article;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $address = new Address();
        $address->setStreet("10 rue de la Paix");
        $address->setZipcode("75000");
        $address->setCity("Paris");

        $author = new Person();
        $author->setFirstname("Pierre");
        $author->setLastname("Ecrivain");
        $author->setEmail("pierre.ecrivain@test.com");
        $author->setBirthdate(new \DateTime('1985-05-20'));
        $author->setAddress($address);

        $manager->persist($author);

        for ($i = 1; $i <= 5; $i++) {
            $article = new Article();
            $article->setTitle("Mon super article n°" . $i);
            $article->setContent("Ceci est le contenu passionnant de l'article numéro " . $i);
            $article->setCreatedAt(new \DateTimeImmutable());
            
            $article->setAuthor($author);

            $manager->persist($article);
        }

        $manager->flush();
    }
}