<?php

namespace App\DataFixtures;

use App\Entity\Frequency;
use App\Entity\WeeklySchedule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $daysEnglish = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        foreach ($days as $key => $day) {
            $weeklySchedule = new WeeklySchedule();
            $weeklySchedule->setDay($day)
                ->setDayEnglish($daysEnglish[$key])
                ->setMorningOpening('10:00')
                ->setMorningClosing('14:00')
                ->setEveningOpening('16:00')
                ->setEveningClosing('21:00');
            $manager->persist($weeklySchedule);
        }
        $frequency = new Frequency();
        $frequency->setValue(10)
            ->setActive(true);
        $manager->persist($frequency);
        $manager->flush();
    }
}
