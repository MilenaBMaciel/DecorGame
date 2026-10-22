<?php

namespace App\DataFixtures;

use App\Entity\Mobile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MobileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $mobiles = [
            ['name' => 'Sofa',      'image' => 'sofa.png'],
            ['name' => 'Table',     'image' => 'table.png'],
            ['name' => 'Chair',     'image' => 'chair.png'],
            ['name' => 'Bed',       'image' => 'bed.png'],
            ['name' => 'Bookshelf', 'image' => 'bookshelf.png'],
        ];

        foreach ($mobiles as $data) {
            $mobile = new Mobile();
            $mobile->setName($data['name']);
            $mobile->setImage($data['image']);
            $manager->persist($mobile);
        }

        $manager->flush();
    }
}