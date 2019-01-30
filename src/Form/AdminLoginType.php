<?php

namespace App\Form;

use App\Entity\AdminLogin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Captcha\Bundle\CaptchaBundle\Form\Type\SimpleCaptchaType;

class AdminLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, ["label" => "Prénom", "attr" => ["placeholder" => "Entrez votre prénom"]])
            ->add('nom', TextType::class, ["label" => "Nom", "attr" => ["placeholder" => "Entrez votre nom"]])
            ->add('email', TextType::class, ["label" => "Email", "attr" => ["placeholder" => "Entrez votre adresse email"]])
            ->add('profil', ChoiceType::class, ["choices" => ["Je suis un particulier" => "Particulier", "Je suis un profesionnel" => "Professionnel"]])
            ->add('username', TextType::class, ["label" => "Nom d'utilisateur", "attr" => ["placeholder" => "Minimum 6 caractères."]])
            ->add("password", RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les deux mots de passe doivent être identiques.",
                "options" => ["attr" => ["class" => "password-field"]],
                "required" => true,
                "first_options" => ["label" => "Mot de passe", "attr" => ["placeholder" => "Minimum 8 caractères"]],
                'second_options' => ['label' => 'Confirmation du mot de passe', "attr" => ["placeholder" => "Minimum 8 caractères"]],
            ]);
            // ->add('captchaCode', SimpleCaptchaType::class, array(
            //     'captchaStyleName' => 'ExampleCaptcha'
            // ))
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdminLogin::class,
        ]);
    }
}
