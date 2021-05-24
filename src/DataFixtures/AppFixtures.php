<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i < 10; $i++) {
            $category = (new Category())->setName($i . '-' . $faker->company);
            for ($j = 0; $j < 10; $j++) {
                $subCategory = (new SubCategory())->setName($i . '-' . $faker->name)->setCategory($category);
                $manager->persist($subCategory);
            }
            $manager->persist($category);
        }
        $manager->flush();
    }
}
