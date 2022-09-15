<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new Products();
        $product1->setManufacturer('Apple');
        $product1->setName('Iphone 11');
        $product1->setDescription('Trop cool');
        $product1->setScreen('6.8 inches');
        $product1->setPrice('1000');
        $product1->addCustomer($this->getReference('customer1'));
        $product1->initializeSlug();
        $manager->persist($product1);

        $product2 = new Products();
        $product2->setManufacturer('Huawei');
        $product2->setName('P50 pro');
        $product2->setDescription('Trop cher');
        $product2->setScreen('6.6 inches');
        $product2->setPrice('1000');
        $product2->addCustomer($this->getReference('customer2'));
        $product2->initializeSlug();
        $manager->persist($product2);

        $product3 = new Products();
        $product3->setManufacturer('Samsung');
        $product3->setName('Galaxy S21');
        $product3->setDescription('Pas terrible');
        $product3->setScreen('6.8 inches');
        $product3->setPrice('1000');
        $product3->addCustomer($this->getReference('customer3'));
        $product3->initializeSlug();
        $manager->persist($product3);

        $manager->flush();
    }
}
