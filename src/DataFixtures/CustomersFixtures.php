<?php

namespace App\DataFixtures;

use App\Entity\Customers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomersFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $customer1 = new Customers();
        $customer1->setFirstname('customer1');
        $customer1->setSurname('customer1');
        $customer1->setEmail('customer1@test.com');
        $customer1->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($customer1, 'Customer1');
        $customer1->setPassword($password);
        $manager->persist($customer1);

        $customer2 = new Customers();
        $customer2->setFirstname('customer2');
        $customer2->setSurname('customer2');
        $customer2->setEmail('customer2@test.com');
        $customer2->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($customer2, 'Customer2');
        $customer2->setPassword($password);
        $manager->persist($customer2);

        $customer3 = new Customers();
        $customer3->setFirstname('customer3');
        $customer3->setSurname('customer3');
        $customer3->setEmail('customer3@test.com');
        $customer3->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($customer3, 'Customer3');
        $customer3->setPassword($password);
        $manager->persist($customer3);

        $customer4 = new Customers();
        $customer4->setFirstname('customer4');
        $customer4->setSurname('customer4');
        $customer4->setEmail('customer4@test.com');
        $customer4->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($customer4, 'Customer4');
        $customer4->setPassword($password);
        $manager->persist($customer4);

        $manager->flush();

        // Reference
        $this->addReference('customer1', $customer1);
        $this->addReference('customer2', $customer2);
        $this->addReference('customer3', $customer3);
        $this->addReference('customer4', $customer4);
    }
}
