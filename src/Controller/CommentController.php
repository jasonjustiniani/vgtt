<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\Type\CommentAddType;
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
        //retrieve all top-level comments (where parent_comment_id is null) and order them by date_created in descending order
        $comments = $this->entityManager->getRepository(Comments::class)->findAllComments($paginator, $request);

        // Create a new comment and form for adding comments
        $comment = new Comments();
        $form = $this->createForm(CommentAddType::class, $comment);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    // Save the new comment to the database
                    $this->entityManager->persist($comment);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'Your comment has been posted.');
                } catch (\Exception $e) {
                    // Handle any exceptions that occur during database operations
                    $this->addFlash('error', 'Failed to add comment. Please try again.');
                }
                // Redirect to the homepage to prevent form resubmission
                return $this->redirectToRoute('homepage'); 
            } else {
                // If the form is not valid, add an error flash message
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

    // View a single comment and its replies, we only accett numeric id, if the comment is a reply, we throw 404
    #[Route('/{id<\d+>}/view', name: 'view_comment')]
    public function viewComment(Request $request, PaginatorInterface $paginator, CommentsService $commentsService, #[MapEntity(id: 'id')] Comments $comment): Response
    {
        // If the comment is a reply (i.e., it has a parent_comment_id), throw a 404 error
        if($comment->getParentCommentId() !== NULL) {
            throw $this->createNotFoundException('The post does not exist');
        }

        // Retrieve the replies for the given comment, ordered by date_created in descending order
        $replies = $this->entityManager->getRepository(Comments::class)->getReplies($paginator, $request, $comment->getId());

        // Create a new reply and form for adding replies to the comment
        $reply = new Comments();
        $form = $this->createForm(RepliesAddType::class, $reply);

        // Handle form submission for adding a reply
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
