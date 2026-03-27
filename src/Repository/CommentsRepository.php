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

    // Retrieve all top-level comments (where parent_comment_id is null) and order them by date_created in descending order, we also add pagination here
    public function findAllComments($paginator, $request): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
        ->andWhere('c.parent_comment_id IS NULL')
        ->orderBy('c.date_created', 'DESC');
        return $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
    }

    // Retrieve the replies for a given comment, ordered by date_created in descending order, we also add pagination here
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

    // Get the number of replies for a given comment
    public function countReplies(int $commentId): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.parent_comment_id = :commentId')
            ->setParameter('commentId', $commentId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
