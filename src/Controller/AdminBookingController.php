<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher la liste des reservations
     * 
     * @Route("/admin/bookings/index", name="admin_bookings_index")
     * 
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/bookings/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'editer une reservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @return void
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager)
    {
        
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a bien été modifiée"
            );
            return $this->redirectToRoute("admin_bookings_index");
        }
        return $this->render('admin/bookings/edit.html.twig',[
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une réservation
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     * 
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager)
    {
        $manager->remove($booking);
        $manager->flush();
        $this->addflash(
            'success',
            "La réservation a bien été supprimée"
        );
        return $this->redirectToRoute("admin_bookings_index");
    }
}
