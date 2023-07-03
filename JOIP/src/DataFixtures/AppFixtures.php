<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Apprenant;
use App\Entity\Inscription;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $apprenant = new Apprenant();
        $apprenant->setAddress('hay salam ')
            ->setName('ahemd')
            ->setMail('mynbi@example.com')
            ->setPhone('0123456789')
            ->setGender('male');
        $manager->persist($apprenant);

        $event1 = new Event();
        $event1->setTitre('Event 1');
        $event1->setDescription('This is event 1');
        $event1->setParticipent(100);
        $event1->setDebutAt(new \DateTimeImmutable('2023-05-20 10:00:00'));
        $event1->setFinishAt(new \DateTimeImmutable('2023-05-20 12:00:00'));
        // Set other properties if needed...

        $manager->persist($event1);

        $event2 = new Event();
        $event2->setTitre('Event 2');
        $event2->setDescription('This is event 2');
        $event2->setParticipent(50);
        $event2->setDebutAt(new \DateTimeImmutable('2023-05-21 14:00:00'));
        $event2->setFinishAt(new \DateTimeImmutable('2023-05-21 16:00:00'));
        // Set other properties if needed...

        $manager->persist($event2);

        $inscription1 = new Inscription();
        $inscription1->setEvent($event1); // Replace $eventObject with the actual Event object
        $inscription1->setAppenant($apprenant); // Replace $apprenantObject with the actual Apprenant object

        $manager->persist($inscription1);

        $inscription2 = new Inscription();
        $inscription2->setEvent($event2); // Replace $eventObject with the actual Event object
        $inscription2->setAppenant($apprenant); // Replace $apprenantObject with the actual Apprenant object

        $manager->persist($inscription2);

        $manager->flush();
    }
}
