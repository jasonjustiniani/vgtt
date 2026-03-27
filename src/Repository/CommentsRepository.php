<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Comments>
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
        )
    {
        parent::__construct($registry, Comments::class);
    }

    public function findAllComments($paginator, $request): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        // return $paginator->paginate(
        //     $this->createQueryBuilder('c')
        //         ->andWhere('c.parent_comment_id IS NULL')
        //         ->orderBy('c.date_created', 'DESC'),
        //     1, /* page number */
        //     10 /* limit per page */
        // );
        $query = $this->createQueryBuilder('c')
        ->andWhere('c.parent_comment_id IS NULL')
        ->orderBy('c.date_created', 'DESC');
        return $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
        // return $this->findBy([], ['date_created' => 'DESC']);
    }

    public function getReplies($paginator, $request, int $commentId):  \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
        ->andWhere('c.parent_comment_id = :commentId')
        ->setParameter('commentId', $commentId)
        ->orderBy('c.date_created', 'DESC');
        return $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
    }

    public function countReplies(int $commentId): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.parent_comment_id = :commentId')
            ->setParameter('commentId', $commentId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Comments[] Returns an array of Comments objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comments
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
