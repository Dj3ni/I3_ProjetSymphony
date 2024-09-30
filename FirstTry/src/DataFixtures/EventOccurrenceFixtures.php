<?php

namespace App\DataFixtures;

use App\Entity\EventOccurrence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventOccurrenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10 ; $i++) { 
            // 1. Get event references
            $event = $this->getReference("event$i");

            // 2. Create occurrences
            $currentStartDate = clone $event->getDateStart();
            $currentEndDate = clone $event->getDateEnd();
            for ($j=0; $j < $event->getRecurrenceCount(); $j++) { 
                $occurrence = new EventOccurrence();
                $occurrence->setEvent($event)
                            ->setDateStart(clone $currentStartDate)
                            ->setDateEnd(clone $currentEndDate);
                    // Increment dates
                    $currentStartDate->modify("+1 day");
                    $currentEndDate->modify("+1 day");
                    $manager->persist($occurrence);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return([
            EventFixture::class,
        ]);
    }
}
