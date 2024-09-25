<?php

namespace App\DataFixtures;


use App\DataFixtures\EventFixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\GamingPlaceFixture;
use App\Entity\EventPlace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventPlaceFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $event = $this->getReference("event$i");
            foreach (range(0, rand(1,3)) as $j) { // I want 1 to 3 places for each event
                $gamingPlace = $this->getReference("gamingPlace".rand(30,39)); // intervals in GamePlace fixture
                // Create a new Object to link them
                $eventPlace = new EventPlace();
                $eventPlace->setEvent($event);
                $eventPlace->setGamingPlace($gamingPlace);
                $manager->persist($eventPlace);
            }
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return([
            GamingPlaceFixture::class,
            EventFixture::class,
        ]);
    }
}
