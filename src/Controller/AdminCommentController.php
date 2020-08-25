<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher l'index de l'edition de commentaires
     * 
     * @Route("/admin/comments", name="admin_comments_index")
     * 
     * @return Response
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comments/index.html.twig', [
            'comments' => $repo->findAll()
        ]);
    }

    /**
     * Formulaire d'edition de commentaire
     *
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le commentaire <strong>{$comment->getId()}</strong> a bien été modifié !"
            );
            return $this->redirectToRoute('admin_comments_index');
        }
        return $this->render('admin/comments/edit.html.twig',[
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return void
     */
    public function delete(Comment $comment, ObjectManager $manager)
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'success',
            "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimé"
        );
        return $this->redirectToRoute("admin_comments_index");
    }
}
