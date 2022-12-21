<?php

namespace App\Controller;

use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{   #[Route('/test/biblio', name: 'app_test_biblio')]
    #[IsGranted("ROLE_BIBLIO")]
    public function biblio(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }


    /**
     * @Route("/test", name="app_test")
     * c'est la première route du projet (ligne au-dessus, c'est l'ancienne version bis php7). Ce qui commence par # est une annotation attribut de symfony sans espace après le #
     Une méthode d'un controller qui est liée à une route DOIT retourner un objet de classe Response
     */
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        echo "c'est la route test";
        exit; // die;
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
        echo "cette ligne ne sera jamais exécutée";
    }

    #[Route('/nouvelle-route', name: 'app_test_nouvelle')]
    public function test2(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    /* 
    EXERCICE 1 : ajouter une nouvelle route pour l'url "/exercice1" qui affiche le texte suivant : "Voici la solution de l'exercice". Vous devez utiliser un nouveau fichier twig pour cet affichage
    */

    #[Route('/exercice1')]
    public function exercice1(): Response
    {
        return $this->render('test/exercice1.html.twig', [
            'a' => 5.2,
        ]);
    }

    /* Création d'une route paramétrée */
    /**
     Dans le chemin d'une route, la partie entre {} est un paramètre, c'est-à-dire que c'est une partie dynamique du chemin.
     L'option requirements permet de mettre une contrainte sur les paramètres d'une route.
        \d+ est une expression régulière (REGEX) signifiant que ce que l'on cherche est composé de minimum un chiffre et autant qu'on veut ensuite (si on veut limiter à deux chiffres on met \d{2})
        \d* tous les chiffres possibles
        . n'importe quels caractères
        [a-z] que des lettres minuscules
        [a-zA-Z]+ pour ajouter les majuscules ; en ajoutant le +, c'est un string d'1 caractère minimum et autant que l'on veut derrière
     */

    #[Route('/route-parametree/{a}', name: 'app_test_param', requirements: ['a' => '\d+'])]
    public function param($a): Response
    {
        return $this->render('test/exercice1.html.twig', [
            'a' => $a,
        ]);
    }

    /* EXO : ajouter une route dont le chemin commence par salutation suivi d'un paramètre nommé prenom
    Cette route doit afficher "Bonjour prenom"
    ⚠ prenom sera remplacé par le prénom qui sera tapé dans l'url, par exemple"/salutation/gertrude" 
    */

    #[Route('/salutation/{prenom}', name: 'app_test_salutation')]
    public function salutation($prenom): Response
    {
        return $this->render('test/salutation.html.twig', [
            'prenom' => $prenom,
        ]);
    }


    #[Route('/boucles', name: 'app_test_boucles')]
    public function boucles()
    {
        $tableau = [ "bonjour", "prenom", 45, true, 78.5 ];
        return $this->render('test/boucles.html.twig', [ 'table' => $tableau ]);
    }


    #[Route('/tableau-objet', name: 'app_test_tableau_objet')]
    public function tableauObjet()
    {
        $tableau = [ 
            "nom"       => "Onim",
            "prenom"    => "Anne",
            "age"       => 20
        ];

        $objet = new stdClass;
        $objet->nom = "Ateur";
        $objet->prenom = "Nordine";
        $objet->age = 32;

        return $this->render("test/tableau.html.twig", [
            "tableau" => $tableau,
            "objet"   => $objet
        ]);
    }

    #[Route('/calcul/{nb1}/{nb2}', name: 'app_test_calcul', requirements: ["nb1" => "\d+", "nb2" => "[0-9]+"])]
    public function calcul($nb1, $nb2)
    {
        return $this->render('test/calcul.html.twig', ['nb1' => $nb1, 'nb2' => $nb2]);
    }
    
}
