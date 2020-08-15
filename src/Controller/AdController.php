<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * permet de creer une annonce
     * 
     * @Route("/ads/new", name="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)  //on fait appel à la classe request permettant de recuperer les données du formulaire et à objectmanager de doctrine
    {
        $ad = new Ad;

        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);   //on demande à ce que les données qui seront rentrées dans le formulaire+ soient recuperées et mise en relation avec ad
        
        if ($form->isSubmitted() && $form->isValid()) {  //si ca a bien ete soumis et valide
            foreach($ad->getImages() as $image)   //pour chaque images (hors coverImage)
            {
                $image->setAd($ad);         //on associe l'image à l'entité ad
                $manager->persist($image);      //on la fait persister
            }
            $ad->setAuthor($this->getUser());
            //$manager = $this->getDoctrine()->getManager()
            $manager->persist($ad);                      //on demande à ce que les données issues du formulaire reste de facon persistante dans ad
            $manager->flush();                           //on envoie la requete sql

            $this->addFlash(                             //on met un message success
                'success',
                "l'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [   //on redirige vers l'annonce
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher la page d'edition d'une annonce
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     */
    public function edit(Request $request, Ad $ad, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {  //si ca a bien ete soumis et valide
            foreach($ad->getImages() as $image)   //pour chaque images (hors coverImage)
            {
                $image->setAd($ad);         //on associe l'image à l'entité ad
                $manager->persist($image);      //on la fait persister
            }
            //$manager = $this->getDoctrine()->getManager() //remplacé par l'appel d'objectManager dans les params
            $manager->persist($ad);                      //on demande à ce que les données issues du formulaire reste de facon persistante dans ad
            $manager->flush();                           //on envoie la requete sql

            $this->addFlash(                             //on met un message success
                'success',
                "l'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('ads_show', [   //on redirige vers l'annonce
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/edit.html.twig',[
            'form' => $form->createView(),
            'ad' => $ad
            ]);
    }

    /**
     * permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad)
    {
        //on recupere l'annonce qui correspond au slug 
        //$ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}
