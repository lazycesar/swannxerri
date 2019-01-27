<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ["label" => "Titre"])
            ->add('video', TextType::class, ["label" => "Vidéo", "attr" => ["placeholder" => "Copier ici le lien d'intégration YouTube de la vidéo"]])
            ->add('description', TextType::class, ["label" => "Description"])
            ->add('length', TextType::class, ["label" => "Durée", "attr" => ["placeholder" => "Exemple - 00:00"]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
