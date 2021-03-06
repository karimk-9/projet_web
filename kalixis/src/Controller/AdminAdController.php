<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/annonces", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'affciher le formulaire d'édition
     *@Route("/admin/annonce/{id}/edit",name="admin_ads_edit")
     * @param Ad $ad
     * @return void
     */
    public function edit(Ad $ad, Request $request,EntityManagerInterface $manager){
        $form=$this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce a bien été modifée"
            );
        }
        return $this->render('admin/ad/edit.html.twig',[
            'ad'=>$ad,
            'form'=> $form->createView()
        ]);
    }
    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/annonce/{id}/delete", name="admin_ads_delete")
     * 
     */
    public function delete(Ad $ad,EntityManagerInterface $manager){

        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer une annonce liée à des réservations"
            );
        }
        else {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()} </strong> a bien été supprimée."
        );}
        return $this->redirectToRoute('admin_ads_index');

    }
}
