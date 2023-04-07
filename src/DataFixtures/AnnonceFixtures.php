<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AnnonceFixtures extends Fixture
{
    private $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface) 
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }
    
    public function load(ObjectManager $manager ): void
    {

    $user1 = new User();
    $user1->setEmail('riri@gmail.com',);
    $user1->setPseudo('riri');
    $user1->setPassword(
        $this->userPasswordHasherInterface ->hashPassword(
            $user1,
            'azerty'
        )
    );
    $user1->setRoles(["ROLE_USER"]);


    $user2 = new User();
    $user2->setEmail('fifi@gmail.com',);
    $user2->setPseudo('fifi');
    $user2->setPassword(
        $this->userPasswordHasherInterface ->hashPassword(
            $user1,
            'azerty'
        )
    );
    $user2->setRoles(["ROLE_USER"]);

    $user3 = new User();
    $user3->setEmail('loulou@gmail.com');
    $user3->setPseudo('loulou');
    $user3->setPassword(
        $this->userPasswordHasherInterface ->hashPassword(
            $user3,
            'azerty'
        )
    );
    $user3->setRoles(["ROLE_USER"]);
    
    $annoncesItem = [
        [
            'name' => 'T2 60m2',
            'description' => 'Loue jolie T2 de 60m2 meublé',
            'price' => '575',
            'user' => $user1,
            'is_visible' => true,
        ],
        [
            'name' => 'Télé 50"',
            'description' => 'Vend télé 50" comme neuve',
            'price' => '300',
            'user' => $user1,
            'is_visible' => true,
        ],
        [
            'name' => 'Lampe de bureau',
            'description' => 'Petite lampe de bureau jamais utilisée',
            'price' => '5.30',
            'user' => $user2,
            'is_visible' => true,
        ],
        [
            'name' => 'Chaton Siamois',
            'description' => 'Vend chaton siamois de 4 mois vaccinés',
            'price' => '160',
            'user' => $user3,
            'is_visible' => false,
        ],
        [
            'name' => 'Xenoblade 3',
            'description' => 'Vend cartouche xenoblade 3 sans la boite',
            'price' => '35',
            'user' => $user3,
            'is_visible' => true,
        ],
    ];

    foreach( $annoncesItem as $item)
    {
        $annonce = new Annonce();
        $annonce->setName($item['name']);
        $annonce->setDescription($item['description']);
        $annonce->setPrice($item['price']);
        $annonce->setUser($item['user']);
        $annonce->setIsVisible($item['is_visible']);
        $manager->persist($annonce);
    }



        $manager->flush();
    }
}
