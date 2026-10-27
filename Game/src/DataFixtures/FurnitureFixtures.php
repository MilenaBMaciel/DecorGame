<?php

namespace App\DataFixtures;

use App\Entity\Furniture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FurnitureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $furnitureItems = [
            ['name' => 'Sofa',      'image' => 'sofa.png'],
            ['name' => 'Table',     'image' => 'table.png'],
            ['name' => 'Chair',     'image' => 'chair.png'],
            ['name' => 'Bed',       'image' => 'bed.png'],
            ['name' => 'Bookshelf', 'image' => 'bookshelf.png'],
        ];

        foreach ($furnitureItems as $data) {
            $furniture = new Furniture();
            $furniture->setName($data['name']);
            $furniture->setImage($data['image']);
            $manager->persist($furniture);
        }

        $manager->flush();
    }
}