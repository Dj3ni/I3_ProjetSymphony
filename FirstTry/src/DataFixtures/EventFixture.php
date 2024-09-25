<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\GamingPlace;
use App\Enum\EventType;
use App\Enum\RecurrenceType;
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
                "recurrenceEnd"=>$faker->dateTime(),
                "recurrenceCount"=> 3,
                "description" => $faker->paragraph(),
                "fee"=>$faker->randomFloat(2,0,100),

            ]);
            for ($j = 0; $j < 2; $j++) { 
                
            }
            $event->setEventType(EventType::BOARDGAMES_DEMO);
            


            $event->setRecurrenceType(RecurrenceType::WEEKLY);

            $manager->persist($event);
        }

        $manager->flush();
    }
}
