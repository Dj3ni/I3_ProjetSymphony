<?php

namespace App\DataFixtures;

use App\Entity\GamingPlace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GamingPlaceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        for ($i=0; $i < 10; $i++) { 
            $gamingPlace = new GamingPlace([
                "name" => $faker->company(),
                "type" => $faker->word(),
                "description" => $faker->paragraph(),
                "placeMax"=>$faker->randomNumber(3, false),
            ]);
            $manager->persist($gamingPlace);
        }

        $manager->flush();
    }
}
