<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        
        for ($i=0; $i < 10; $i++) { 
            $event = new Event([
                "title" => $faker->catchPhrase(),
                "dateStart" => $faker->dateTime(),
                "dateEnd" => $faker->dateTime(),
                "description" => $faker->paragraph(),
                "fee"=>$faker->randomFloat(2,0,100),
            ]);
            $manager->persist($event);
        }

        $manager->flush();
    }
}
