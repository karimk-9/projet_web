<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class, $this->getConfiguration("Prénom",""))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom",""))
            ->add('email', EmailType::class, $this->getConfiguration("Email",""))
            ->add('picture', UrlType::class, $this->getConfiguration("Photos de profil","Url de votre avatar"))
            ->add('introduction', TextType::class,$this->getConfiguration("Introduction","Présentez-vous en quelques mots..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Description détaillée",""))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
