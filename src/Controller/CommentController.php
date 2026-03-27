<?php

namespace App\Controller;

use App\Entity\Comments;
use app\Form\Type\CommentAddType;
use App\Form\Type\RepliesAddType;
use App\Service\CommentsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

final class CommentController extends AbstractController
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/', name: 'homepage')]
    public function index(Request $request, CommentsService $commentsService, PaginatorInterface $paginator): Response
    {
        $comments = $this->entityManager->getRepository(Comments::class)->findAllComments($paginator, $request);

        $comment = new Comments();
        $form = $this->createForm(CommentAddType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->entityManager->persist($comment);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'Your comment has been posted.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Failed to add comment. Please try again.');
                }
                return $this->redirectToRoute('homepage'); // Redirect to prevent form resubmission
            } else {
                $this->addFlash('error', 'Please correct the errors in the form.');
            }
        }
     
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
            'comments' => $comments,
            'form' => $form->createView(),
            'commentsService' => $commentsService,
        ]);
    }

    #[Route('/{id}/view', name: 'view_comment')]
    public function viewComment(Request $request, PaginatorInterface $paginator, CommentsService $commentsService, #[MapEntity(id: 'id')] Comments $comment): Response
    {
        // If the comment is a reply, redirect to homepage
        if($comment->getParentCommentId() !== NULL) {
            return $this->redirectToRoute('homepage');
        }
        $replies = $this->entityManager->getRepository(Comments::class)->getReplies($paginator, $request, $comment->getId());
        $reply = new Comments();
        $form = $this->createForm(RepliesAddType::class, $reply);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->entityManager->persist($reply);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'Your reply has been posted.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Failed to add reply. Please try again.');
                }
                return $this->redirectToRoute('view_comment', ['id' => $comment->getId()]); // Redirect to prevent form resubmission
            } else {
                $this->addFlash('error', 'Please correct the errors in the form.');
            }
        }

        return $this->render('comment/view.html.twig', [
            'controller_name' => 'CommentController',
            'comment' => $comment,
            'replies' => $replies,
            'commentsService' => $commentsService,
            'form' => $form->createView(),
        ]);
    }
}
