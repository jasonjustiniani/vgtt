<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;

class CommentsService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function getReplyCount(int $commentId): int
    {
        return $this->entityManager->getRepository(Comments::class)->countReplies($commentId);
    }
}