<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('resume')
            ->add('couverture', FileType::class, [
                /* L'option "mapped" avec la valeur false permet de préciser que le champ ne sera pas lié à une propriété de l'objet utilisé pour afficher le formulaire */
                "mapped" => false,
                "required" =>false
            ])
            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "btn btn-secondary", "id" => "btEnregistrer"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
