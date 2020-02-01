<?php

namespace App\DataFixtures;

use App\Entity\Account\User;
use App\Entity\Category\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();

        $userAdmin->setGender('Monsieur')
                  ->setFirstname('Teddy')
                  ->setLastname('Lecomte')
                  ->setEmail('lecomteteddy@gmail.com')
                  ->setPassword($this->encoder->encodePassword($userAdmin, "password"))
                  ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($userAdmin);

        $category1 = new Category();

        $category1->setTitle('Category 1')
                  ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse 
                  quis eros elit. Donec ut leo sapien. In hac habitasse platea dictumst. Sed vestibulum euismod tristique. 
                  Fusce ac egestas massa. Phasellus vestibulum odio felis, in euismod est ornare ac. 
                  Donec euismod malesuada venenatis. Curabitur at quam blandit, vehicula ligula ut, euismod justo.')
                 ->setPicture("https://picsum.photos/id/50/500/500")
            ->setCreatedBy($userAdmin)
        ;

        $category2 = new Category();

        $category2->setTitle('Category 2')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse 
                  quis eros elit. Donec ut leo sapien. In hac habitasse platea dictumst. Sed vestibulum euismod tristique. 
                  Fusce ac egestas massa. Phasellus vestibulum odio felis, in euismod est ornare ac. 
                  Donec euismod malesuada venenatis. Curabitur at quam blandit, vehicula ligula ut, euismod justo.')
            ->setPicture("https://picsum.photos/id/60/500/500")
            ->setCreatedBy($userAdmin)
        ;

        $category3 = new Category();

        $category3->setTitle('Category 3')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse 
                  quis eros elit. Donec ut leo sapien. In hac habitasse platea dictumst. Sed vestibulum euismod tristique. 
                  Fusce ac egestas massa. Phasellus vestibulum odio felis, in euismod est ornare ac. 
                  Donec euismod malesuada venenatis. Curabitur at quam blandit, vehicula ligula ut, euismod justo.')
            ->setPicture("https://picsum.photos/id/70/500/500")
            ->setCreatedBy($userAdmin)
        ;

        $category4 = new Category();

        $category4->setTitle('Category 4')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse 
                  quis eros elit. Donec ut leo sapien. In hac habitasse platea dictumst. Sed vestibulum euismod tristique. 
                  Fusce ac egestas massa. Phasellus vestibulum odio felis, in euismod est ornare ac. 
                  Donec euismod malesuada venenatis. Curabitur at quam blandit, vehicula ligula ut, euismod justo.')
            ->setPicture("https://picsum.photos/id/80/500/500")
            ->setCreatedBy($userAdmin)
        ;

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);
        $manager->persist($category4);

        $manager->flush();
    }
}
