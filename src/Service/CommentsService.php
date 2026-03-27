<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;

class CommentsService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    // Get the number of replies for a given comment, we add this here so that we can call it from twig
    public function getReplyCount(int $commentId): int
    {
        return $this->entityManager->getRepository(Comments::class)->countReplies($commentId);
    }
}