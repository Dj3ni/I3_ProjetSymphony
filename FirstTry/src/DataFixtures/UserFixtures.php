<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // Hasher de pwd
    private UserPasswordHasherInterface $pwdHasher;

    // Insert it in the construct function
    public function __construct(UserPasswordHasherInterface $pwdHasher)
    {
        $this->pwdHasher = $pwdHasher;
    }
    
    // Load everything in the fixture
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        
        for ($i=0; $i < 10; $i++) { 
            $user = new User([
                "firstname" => $faker->firstName(),
                "lastname" => $faker->lastName(),
                "phoneNumber" => $faker->phoneNumber(),
                "email"=> "user". $i . "@mail.com",
            ]);
            $pwdHashed =$this->pwdHasher->hashPassword($user,$faker->word());
            $user->setPassword($pwdHashed);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
