<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * Ordem oficial das salas no jogo: ordem de criação (id).
     * A lista e os botões anterior/próximo usam a mesma ordem.
     *
     * @return Room[]
     */
    public function findAllOrdered(): array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    // Próxima sala (id maior mais próximo); null se esta for a última
    public function findNext(int $id): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id > :id')
            ->setParameter('id', $id)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // Sala anterior (id menor mais próximo); null se esta for a primeira
    public function findPrevious(int $id): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id < :id')
            ->setParameter('id', $id)
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Room[] Returns an array of Room objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Room
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
