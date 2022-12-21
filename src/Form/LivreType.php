<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class LivreType extends AbstractType
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
            ->add('auteur', EntityType::class, [
                "class" => Auteur::class,
                "choice_label" => function($auteur) {
                    // dans le select, chaque option sera affiché avec le prénom et le nom de l'auteur
                    return $auteur->getPrenom() . " " . $auteur->getNom();
                },
                "placeholder" => "Choisir un auteur..."
            ])
            ->add('genres', EntityType::class, [
                "class" => Genre::class,
                "choice_label" => "libelle", // le nom de la propriété affichée comme valeur du champ 'genres'
                "multiple" => true, //signifie que plusisurs valeurs sont possibles (type array, par exemple)
                "expanded" => true, // pemret de choisir comment le champ va être affiché (checkbox, radio buttons, etc.)
                "attr" => [
                    "class" => "cac"
                ]
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
