<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        
        for ($i=0; $i < 10; $i++) { 
            $address = new Address([
                "street" => $faker->streetName(),
                "number" => $faker->buildingNumber(),
                "postCode" => $faker->postcode(),
                "city"=>$faker->city(),
                "country"=> "Belgium"
            ]);
            $manager->persist($address);
        }

        $manager->flush();
    }
}
