<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\Slot;
use App\Entity\User;
use App\Repository\SlotRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * As salas e os móveis são globais (criados pelo admin), mas a decoração é de cada usuário.
 * Os slots de um usuário numa sala são criados vazios na primeira vez que ele abre a sala.
 */
class SlotManager
{
    public function __construct(
        private SlotRepository $slotRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return Slot[] os slots do usuário nesta sala, na ordem da posição (1, 2, 3)
     */
    public function getSlotsFor(Room $room, User $user): array
    {
        $slots = [];
        foreach ($this->slotRepository->findBy(['room' => $room, 'user' => $user]) as $slot) {
            $slots[$slot->getPosition()] = $slot;
        }

        $created = false;
        for ($position = 1; $position <= Room::SLOT_COUNT; ++$position) {
            if (!isset($slots[$position])) {
                $slots[$position] = (new Slot())
                    ->setRoom($room)
                    ->setUser($user)
                    ->setPosition($position);
                $this->entityManager->persist($slots[$position]);
                $created = true;
            }
        }

        if ($created) {
            $this->entityManager->flush();
        }

        ksort($slots);

        return array_values($slots);
    }
}
