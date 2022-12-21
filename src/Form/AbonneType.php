<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Abonné"            => "ROLE_USER",
                    "Lecteur"           =>  "ROLE_LECTEUR",
                    "Bibliothécaire"    => "ROLE_BIBLIO",
                    "Directeur"         => "ROLE_ADMIN"
                ],
                "multiple"  => true,
                "expanded"  => true,
                "label"     => "Accréditations"
            ])
            ->add('password')
            ->add('nom')
            ->add('prenom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
