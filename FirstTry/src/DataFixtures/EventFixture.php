<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Enum\EventType;
use App\Entity\GamingPlace;
use App\Enum\RecurrenceType;
use App\DataFixtures\UserFixtures;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        
        for ($i = 0; $i < 10; $i++) {
            
            // $dateStart = $faker->dateTime();

            $dateStart = new DateTime();

            $event = new Event([
                "title" => $faker->catchPhrase(),
                "dateStart" => $dateStart->modify('+' . rand(0,7) . ' months'),
                "dateEnd" => (clone $dateStart)->modify('+' . rand(0,7) . ' days'),
                "recurrenceEnd"=>$faker->dateTime(),
                "recurrenceCount"=> 3,
                "description" => $faker->paragraph(),
                "fee"=>$faker->randomFloat(2,0,100),
            ]);

            $organisator = $this->getReference("user$i");
            
            $event->setEventType(EventType::cases()[rand(0,4)]);
            
            $event->setRecurrenceType(RecurrenceType::cases()[rand(0,4)]);
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
