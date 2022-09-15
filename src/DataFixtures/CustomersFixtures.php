<?php

namespace App\DataFixtures;

use App\Entity\Customers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customer1 = new Customers();
        $customer1->setFirstname('customer1');
        $customer1->setSurname('customer1');
        $customer1->setEmail('customer1@test.com');
        $customer1->setRole('Admin');
        $manager->persist($customer1);

        $customer2 = new Customers();
        $customer2->setFirstname('customer2');
        $customer2->setSurname('customer2');
        $customer2->setEmail('customer2@test.com');
        $customer2->setRole('Admin');
        $manager->persist($customer2);

        $customer3 = new Customers();
        $customer3->setFirstname('customer3');
        $customer3->setSurname('customer3');
        $customer3->setEmail('customer3@test.com');
        $customer3->setRole('Admin');
        $manager->persist($customer3);

        $customer4 = new Customers();
        $customer4->setFirstname('customer4');
        $customer4->setSurname('customer4');
        $customer4->setEmail('customer4@test.com');
        $customer4->setRole('Admin');
        $manager->persist($customer4);

        $manager->flush();

        // Reference
        $this->addReference('customer1', $customer1);
        $this->addReference('customer2', $customer2);
        $this->addReference('customer3', $customer3);
        $this->addReference('customer4', $customer4);
    }
}
