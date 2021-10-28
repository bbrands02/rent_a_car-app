<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixture extends Fixture {

    private $faker;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {

        $this->faker = Factory::create();
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {
    
        $car = new Car();
        $car->setName('Mercedes');
        $car->setQuantity(10);
        $car->setRentable(10);
        $car->setPrice(1000);
        $car->setImage("/images/97e61bbc-fe95-4fd3-a5ad-0714f31220a1.jpg");
        $car->setDateCreated(new \DateTime());
        $car->setDateModified(new \DateTime());
        $manager->persist($car);

        $user = new User();
        $user->setEmail('barry@rentacar.nl');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test4321'
        ));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setDateCreated(new \DateTime());
        $user->setDateModified(new \DateTime());
        $manager->persist($user);

        $user = new User();
        $user->setEmail('piet@rentacar.nl');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test1234'
        ));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setDateCreated(new \DateTime());
        $user->setDateModified(new \DateTime());
        $manager->persist($user);

        $manager->flush();
    }
}