<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class RoomFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        private Filesystem $filesystem,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // As imagens ficam em fixtures/images/rooms/ (versionadas no git).
        // A ordem aqui é a ordem dos cômodos no jogo (ordem de criação).
        $rooms = [
            ['name' => 'Living Room', 'image' => 'LivingRoom.png'],
            ['name' => 'Kitchen',     'image' => 'Kitchen.png'],
            ['name' => 'Bedroom',     'image' => 'Room.png'],
            ['name' => 'Bathroom',    'image' => 'Bathroom.png'],
        ];

        foreach ($rooms as $data) {
            // Copia a imagem de exemplo para onde o jogo procura as imagens
            $this->filesystem->copy(
                $this->projectDir.'/fixtures/images/rooms/'.$data['image'],
                $this->projectDir.'/public/uploads/rooms/'.$data['image'],
                true,
            );

            $room = new Room();
            $room->setName($data['name']);
            $room->setImage($data['image']);
            // Os slots são criados por usuário, quando ele abre a sala (SlotManager)
            $manager->persist($room);
        }

        $manager->flush();
    }
}
