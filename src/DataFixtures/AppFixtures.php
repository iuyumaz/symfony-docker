<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 4; $i++) {
            $application = new Application();
            $application->setName('test-application-' . $i);
            $application->setCallbackUrl('http://testapp' . $i . '.com/callback');
            $manager->persist($application);
        }
        $manager->flush();
    }

}
