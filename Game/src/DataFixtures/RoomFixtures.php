<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\Slot;
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
            $manager->persist($room);

            // 3 empty slots
            for ($i = 1; $i <= 3; $i++) {
                $slot = new Slot();
                $slot->setRoom($room);
                // mobile null
                $manager->persist($slot);
            }
        }

        $manager->flush();
    }
}