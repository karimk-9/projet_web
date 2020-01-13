<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll() 
        ]);
    }

    /**
     * Permet d'éditer une réservation
     *
     * @Route("/admin/reservations/{id}/edition", name="admin_booking_edit")
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager){

        $form=$this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation a bien été modifiée."

            );

            return $this->redirectToRoute("admin_booking_index");
        }
        return $this->render('admin/booking/edit.html.twig',[
        'form'=>$form->createView(),
        'booking'=> $booking



    ]);
    }

    /**
     * Permet desupprimer une réservation
     * 
     * @Route("/admin/reservations/{id}/delete", name="admin_booking_delete")
     */

    public function delete(Booking $booking, EntityManagerInterface $manager){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée."
        );

        return $this->redirectToRoute("admin_booking_index");
    }
}
