<?php

namespace App\DataFixtures;

use App\Entity\Furniture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class FurnitureFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        private Filesystem $filesystem,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // As imagens ficam em fixtures/images/furniture/ (versionadas no git)
        $furnitureItems = [
            ['name' => 'Table',          'image' => 'Table.png'],
            ['name' => 'Shelf',          'image' => 'Shelf.png'],
            ['name' => 'TV',             'image' => 'TV.png'],
            ['name' => 'Fireplace',      'image' => 'FirePlace.png'],
            ['name' => 'Comfy Sofa',     'image' => 'Diva.png'],
            ['name' => 'Comfy Chair',    'image' => 'ComfyChair.png'],
            ['name' => 'Green Sofa',     'image' => 'GreenSofa.png'],
            ['name' => 'Pink Sofa',      'image' => 'PinkSofa.png'],
            ['name' => 'Purple Sofa',    'image' => 'PurpleSofa.png'],
            ['name' => 'Cat Playground', 'image' => 'CatPlayground.png'],
            ['name' => 'Fridge',         'image' => 'Fridge.png'],
            ['name' => 'Kitchen Sink',   'image' => 'KitchenSink.png'],
            ['name' => 'Green Bed',      'image' => 'GreenBed.png'],
            ['name' => 'Pink Bed',       'image' => 'PinkBed.png'],
            ['name' => 'Purple Bed',     'image' => 'PurpleBed.png'],
            ['name' => 'Dressing Table', 'image' => 'DressingTable.png'],
            ['name' => 'Closet 1',       'image' => 'Closet1.png'],
            ['name' => 'Closet 2',       'image' => 'Closet2.png'],
            ['name' => 'Closet 3',       'image' => 'Closet3.png'],
            ['name' => 'Bathtub',        'image' => 'Bath.png'],
            ['name' => 'Bathroom Sink',  'image' => 'BathSink.png'],
            ['name' => 'Toilet',         'image' => 'Toilet.png'],
        ];

        foreach ($furnitureItems as $data) {
            // Copia a imagem de exemplo para onde o jogo procura as imagens
            $this->filesystem->copy(
                $this->projectDir.'/fixtures/images/furniture/'.$data['image'],
                $this->projectDir.'/public/uploads/furniture/'.$data['image'],
                true,
            );

            $furniture = new Furniture();
            $furniture->setName($data['name']);
            $furniture->setImage($data['image']);
            $manager->persist($furniture);
        }

        $manager->flush();
    }
}
