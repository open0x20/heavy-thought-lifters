<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $customer = new Customer();
        $customer->setEmail('customer1@test.com');
        $customer->setPassword(password_hash('test', PASSWORD_DEFAULT));
        $customer->setLoginTypeId(0);
        $customer->setLastLogin(new \DateTime());
        $manager->persist($customer);

        $manager->flush();
    }
}
