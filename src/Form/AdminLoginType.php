<?php

namespace App\Form;

use App\Entity\AdminLogin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, ["label" => "Prénom", "attr" => ["placeholder" => "Entrez votre prénom"]])
            ->add('nom', TextType::class, ["label" => "Nom", "attr" => ["placeholder" => "Entrez votre nom"]])
            ->add('email', TextType::class, ["label" => "Email", "attr" => ["placeholder" => "Entrez votre adresse email"]])
            ->add('profil', ChoiceType::class, ["choices" => ["Je suis un particulier" => "Particulier", "Je suis un profesionnel" => "Professionnel"]])
            ->add('username', TextType::class, ["label" => "Nom d'utilisateur", "attr" => ["placeholder" => "Votre nom d'utilisateur doit comporter au moins 6 caractères."]])
            ->add('password', PasswordType::class, ["label" => "Mot de passe", "attr" => ["placeholder" => "Choisissez un mot de passe d'au moins 8 caractères."]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdminLogin::class,
        ]);
    }
}
