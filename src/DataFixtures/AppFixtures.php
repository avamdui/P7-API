<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Customer;
use App\Entity\Phone;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture

{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $clients = [];

        // ================================
        // ==========Client=============
        //===============================//

        //---------Orange------------
        $client = new Client();
        $client->setUsername('OrangeAdmin');
        $client->setCompany('Orange');
        $client->setEmail('client1@gmail.com');
        $plaintextPassword = 'Client1';
        $client->setPassword($this->passwordEncoder->hashPassword($client, $plaintextPassword));
        $client->setRoles(['ROLE_USER']);

        $manager->persist($client);
        $clients[] = $client;

        //--------Bouygues-----------
        $client = new Client();
        $client->setUsername('BouyguesAdmin');
        $client->setCompany('Bouygues');
        $client->setEmail('client2@gmail.com');
        $plaintextPassword = 'Client2';
        $client->setPassword($this->passwordEncoder->hashPassword($client, $plaintextPassword));
        $client->setRoles(['ROLE_USER']);

        $manager->persist($client);
        $clients[] = $client;

        //----------SFR--------------
        $client = new Client();
        $client->setUsername('SFRAdmin');
        $client->setCompany('SFR');
        $client->setEmail('client3@gmail.com');
        $plaintextPassword = 'Client3';
        $client->setPassword($this->passwordEncoder->hashPassword($client, $plaintextPassword));
        $client->setRoles(['ROLE_USER']);

        $manager->persist($client);
        $clients[] = $client;

        //--------Admin--------------
        $client = new Client();
        $client->setUsername('BilemoAdmin');
        $client->setCompany('Bilemo');
        $client->setEmail('admin1@gmail.com');
        $plaintextPassword = 'Admin1';
        $client->setPassword($this->passwordEncoder->hashPassword($client, $plaintextPassword));
        $client->setRoles(['ROLE_ADMIN']);

        $manager->persist($client);
        $clients[] = $client;

        // ================================
        // ============Users===============
        //===============================//

        for ($i = 1; $i <= 10; $i++) {
            $customer = new Customer();
            $customer->setFirstname($faker->firstName());
            $customer->setLastname($faker->lastName());
            $customer->setEmail($faker->safeEmail());
            $customer->setPhoneNumber($faker->randomNumber($nbDigits = 10, $strict = true));
            $customer->setPassword('Password');
            $customer->setClient($faker->randomElement($clients));
            $customer->setCreatedAt($faker->dateTimeBetween($startDate = '-8 months', $endDate = 'now', $timezone = null));
            $manager->persist($customer);
        }

        // ================================
        // ==========Phone===========
        //===============================//

        $phone = new Phone();
        $phone->setModelName("Iphone 12")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Apple")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-6 months", $endDate = "now", $timezone = null))
            ->setPrice(1289.89);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("Iphone 13")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Apple")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-3 months", $endDate = "now", $timezone = null))
            ->setPrice(1444.99);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("Lumia")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Nokia")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-4 months", $endDate = "now", $timezone = null))
            ->setPrice(194.49);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("Galaxy S22")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Samsung")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-6 months", $endDate = "now", $timezone = null))
            ->setPrice(989.89);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("Galaxy A51")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Samsung")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-2 months", $endDate = "now", $timezone = null))
            ->setPrice(259.99);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("G8X ThinQ Dual Screen")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("LG")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-2 months", $endDate = "now", $timezone = null))
            ->setPrice(484.90);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("7.2")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Nokia")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-3 months", $endDate = "now", $timezone = null))
            ->setPrice(349.00);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("P40 Pro")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Huawei")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-2 months", $endDate = "now", $timezone = null))
            ->setPrice(949.00);
        $manager->persist($phone);

        $phone = new Phone();
        $phone->setModelName("Galaxy Z Flip")
            ->setRef($faker->randomNumber())
            ->setStock($faker->randomDigit())
            ->setBrand("Samsung")
            ->setDescription("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.")
            ->setCreatedAt($faker->dateTimeBetween($startDate = "-2 months", $endDate = "now", $timezone = null))
            ->setPrice(1242.00);
        $manager->persist($phone);



        $manager->flush();
    }
}
