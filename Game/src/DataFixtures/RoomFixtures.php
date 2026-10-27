<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rooms = [
            ['name' => 'Living Room', 'image' => 'living_room.png'],
            ['name' => 'Kitchen',     'image' => 'kitchen.png'],
            ['name' => 'Bedroom',     'image' => 'bedroom.png'],
        ];

        foreach ($rooms as $data) {
            $room = new Room();
            $room->setName($data['name']);
            $room->setImage($data['image']);
            // Os slots são criados por usuário, quando ele abre a sala (SlotManager)
            $manager->persist($room);
        }

        $manager->flush();
    }
}