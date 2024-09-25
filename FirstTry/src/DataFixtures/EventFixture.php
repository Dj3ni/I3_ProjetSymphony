<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Enum\EventType;
use App\Entity\GamingPlace;
use App\Enum\RecurrenceType;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        
        for ($i = 0; $i < 10; $i++) { 
            $event = new Event([
                "title" => $faker->catchPhrase(),
                "dateStart" => $faker->dateTime(),
                "dateEnd" => $faker->dateTime(),
                "recurrenceEnd"=>$faker->dateTime(),
                "recurrenceCount"=> 3,
                "description" => $faker->paragraph(),
                "fee"=>$faker->randomFloat(2,0,100),
                
            ]);
            $organisator = $this->getReference("user$i");
            
            $event->setEventType(EventType::BOARDGAMES_DEMO);
            
            $event->setRecurrenceType(RecurrenceType::WEEKLY);
            $event->setUserOrganisator($organisator);
            
            $manager->persist($event);

            // Références
            $this->addReference("event$i", $event);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return([
            UserFixtures::class,
        ]);
    }
}
