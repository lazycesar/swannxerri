<?php

namespace App\Form;

use App\Entity\ContactForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Captcha\Bundle\CaptchaBundle\Form\Type\SimpleCaptchaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, ["label" => "Prénom", "attr" => ["placeholder" => "Entrez votre prénom"]])
            ->add('nom', TextType::class, ["label" => "Nom", "attr" => ["placeholder" => "Entrez votre nom"]])
            ->add('email', TextType::class, ["label" => "Email", "attr" => ["placeholder" => "Entrez votre adresse email"]])
            ->add('objet', ChoiceType::class, ["choices" => ["Informations coaching personnel" => "Perso", "Informations coaching professionnel" => "Pro", "Informations conférences" => "Conférence", "Autre demande" => "Autre"]])
            ->add('contenu', TextareaType::class, ["label" => "Message", "attr" => ["placeholder" => "Quel est votre message ?", "rows" => "8" ]])
            ->add('captchaCode', SimpleCaptchaType::class, array(
                'captchaStyleName' => 'ExampleCaptcha'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactForm::class,
        ]);
    }
}
