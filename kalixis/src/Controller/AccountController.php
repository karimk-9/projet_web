<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion 
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error=$utils->getLastAuthenticationError();
        $username = $utils ->getLastUsername();
  

        return $this->render('account/login.html.twig',[
                'hasError'=> $error !== null,
                'username'=> $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * @Route("/logout",name="account_logout")
     *
     * @return void
     */
    public function logout(){
        // ne fais rien
    }

    /**
     * Permet d'afficher le formulaire d'insription
     *@Route("/inscription", name="account_register")
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //Upload de la picture

            /*$file=$form->get('picture')->getData();
            $original_file_name=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $original_file_name);
            $uploads_directory=$this->getParameter('uploads_directory');
        
            $filename= $safeFilename.'-'.uniqid(). '.' .$file->guessExtension(); 
            $user->setPicture($filename);

        $file->move(
                $uploads_directory,
                $filename
            ); */

            $hash= $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager-> persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé, vous pouvez maintenant vous connecter."
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    /**
     * Permet d'afficher et de traiter le formulaire de modification du profil
     * @Route("/compte/profil", name="account_profile")
     * 
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){
        $user=$this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le profil a bien été modifié"
            );
        }
        return $this->render('account/profile.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * Permet de modifier le mot de passe
     * @Route ("/compte/password-update",name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){

        $passwordUpdate= new PasswordUpdate();

        $user = $this->getUser();

        $form =$this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //vérifier que le old password du formulaire soit le même que le password de l'utilisateur
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                // gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel."));

            }
            else{
                $newPassword=$passwordUpdate->getNewPassword();
                $hash= $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié."
                );

                return $this->redirectToRoute('homepage');
            }
            

        }

        return $this->render('account/password.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *@Route("/compte", name="account_index")
     * @return Response
     */
    public function myAccount(){

        return $this->render('user/index.html.twig',[
            'user'=> $this->getUser()
        ]);

    }
    /**
     * Permet d'afficher la liste des réservations faites par l'utilisateur
     * 
     *
     * @return Response
     * @Route("/compte/reservations",name="account_bookings")
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }
}
