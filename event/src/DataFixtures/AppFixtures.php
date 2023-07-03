<?php

namespace App\DataFixtures;

use App\Entity\Dashboard;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $Passwordhasher;
    public function __construct(UserPasswordHasherInterface $Passwordhasher)
    {
        $this->Passwordhasher = $Passwordhasher;
    }
    public function load(ObjectManager $manager): void
    {
        $dashboard = new Dashboard();
        $plainPassword = 'admin';
        $hashedPassword = $this->Passwordhasher->hashPassword($dashboard, $plainPassword);
        $dashboard->setUsername('admin')
        ->setEmail('youssefbouayez2@gmail.com')
            ->setPassword($hashedPassword);
        $manager->persist($dashboard);

        $manager->flush();
    }
}