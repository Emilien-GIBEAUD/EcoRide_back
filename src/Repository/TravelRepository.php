<?php

namespace App\Repository;

use App\Entity\Travel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Travel>
 */
class TravelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Travel::class);
    }

    public function search(array $criteria): array
    {
        $conn = $this->getEntityManager()->getConnection();     // équivalent de $pdo = new PDO(...)
        $sql = "
            SELECT  t.id,
                    t.eco,
                    t.available_place,
                    t.price,
                    t.dep_date_time,
                    t.dep_address,
                    t.arr_date_time,
                    t.arr_address,
                    u.avatar_file,
                    u.pseudo,
                    u.note
            FROM travel AS t
            LEFT JOIN travel_user AS tu ON tu.travel_id = t.id AND tu.travel_role = 'driver'
            LEFT JOIN user AS u ON u.id = tu.user_id
            WHERE status = 'à venir'
                AND dep_date_time >= :date_start
                AND dep_date_time <= :date_end
                AND POW(dep_geo_x - :depGeoX, 2) + POW(dep_geo_y - :depGeoY, 2) <= POW(0.54054, 2)
                AND POW(arr_geo_x - :arrGeoX, 2) + POW(arr_geo_y - :arrGeoY, 2) <= POW(0.54054, 2)
                ORDER BY dep_date_time ASC;
        ";// 60km => 0.54054 en unités géocodées (approximation pour une latitude de 46°)
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'date_start'    => $criteria['depDateTime']->format('Y-m-d 00:00:00'),
            'date_end'      => $criteria['depDateTime']->format('Y-m-d 23:59:59'),
            'depGeoX'       => $criteria['depGeoX'],
            'depGeoY'       => $criteria['depGeoY'],
            'arrGeoX'       => $criteria['arrGeoX'],
            'arrGeoY'       => $criteria['arrGeoY'],
        ]);
        return $result->fetchAllAssociative();
    }

    public function showById(int $id): array
    {
        $conn = $this->getEntityManager()->getConnection();     // équivalent de $pdo = new PDO(...)
        $sql = "
            SELECT  t.id,
                    t.eco,
                    t.available_place,
                    t.price,
                    t.dep_date_time,
                    t.dep_address,
                    t.arr_date_time,
                    t.arr_address,
                    u.avatar_file,
                    u.pseudo,
                    u.note
            FROM travel AS t
            LEFT JOIN travel_user AS tu ON tu.travel_id = t.id AND tu.travel_role = 'driver'
            LEFT JOIN user AS u ON u.id = tu.user_id
            WHERE t.id = :id;
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id, ParameterType::INTEGER);
        $result = $stmt->executeQuery();
        return $result->fetchAllAssociative();
    }

    //    /**
    //     * @return Travel[] Returns an array of Travel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Travel
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
