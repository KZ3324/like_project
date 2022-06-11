<?php

namespace App\Repository;

use App\Entity\LikeRelation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikeRelation>
 *
 * @method LikeRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeRelation[]    findAll()
 * @method LikeRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeRelation::class);
    }

    public function add(LikeRelation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LikeRelation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllLikeById($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $req = 'SELECT COUNT(id_liker) FROM like_relation WHERE id_liker = :id';

        $stmt = $conn->prepare($req);
        $result = $stmt->executeQuery(['id' => $id]);

        return $result->fetchAllAssociative();
    }

    public function likeOrUnlike($iduser, $idliker)
    {
        $conn = $this->getEntityManager()->getConnection();

        // Récupération de l'id user

        $sql_user = 'SELECT id FROM user WHERE username = :iduser';
        $stmt_user = $conn->prepare($sql_user);
        $result_user = $stmt_user->executeQuery(['iduser' => $iduser]);
        $result_user = $result_user->fetchAllAssociative();

        $iduser = $result_user[0]['id'];

        // Vérification si dans BDD or NOT

        $req =
            "SELECT * FROM like_relation WHERE id_liker = :idliker AND user_id = :iduser";

        $stmt = $conn->prepare($req);
        $result = $stmt->executeQuery([
            'idliker' => $idliker,
            'iduser' => $iduser,
        ]);

        $result = $result->fetchAllAssociative();
        return $result;
    }

    //    /**
    //     * @return LikeRelation[] Returns an array of LikeRelation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LikeRelation
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
