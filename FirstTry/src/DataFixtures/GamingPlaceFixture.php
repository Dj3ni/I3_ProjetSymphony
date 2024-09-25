<?php

namespace App\DataFixtures;

use App\Entity\GamingPlace;
use App\DataFixtures\AddressFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GamingPlaceFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        for ($i=30; $i < 40; $i++) { 
            $gamingPlace = new GamingPlace([
                "name" => $faker->company(),
                "type" => $faker->word(),
                "description" => $faker->paragraph(),
                "placeMax"=>$faker->randomNumber(3, false),
            ]);
            $manager->persist($gamingPlace);
            $gamingPlace->setAddress($this->getReference("address$i"));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return([AddressFixture::class]);
    }
}
