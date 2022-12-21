<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Abonne;
use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sortie', DateType::class, [
                "widget" => "single_text", // cette option permet d'afficher l'input de type date 
                "label" => "Date d'emprunt"
            ])
            ->add('retour', DateType::class, [
                "widget" => "single_text", // cette option permet d'afficher l'input de type date 
                "label" => "Date de retour",
                "required" => false
            ])
            ->add('abonne', EntityType::class, [
                "class" => Abonne::class,
                "choice_label" => "pseudo",
                "label" => "Abonné",
                "placeholder" => ""
            ])
            ->add('livre', EntityType::class, [
                "class" => Livre::class,
                "choice_label" => "titre",
                "placeholder" => ""
            ]);        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
