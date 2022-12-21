<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Livre;
use App\Repository\LivreRepository;
use App\Form\FormLivreType;
use Symfony\Component\String\Slugger\AsciiSlugger;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(LivreRepository $lr): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $lr->findAll(), // findAll renvoie tous les enregistrements d'une table
        ]);
    }

    #[Route('/livre/ajouter', name: 'app_livre_ajouter')]
    public function add(Request $request, LivreRepository $livreRepository): Response
    {
        /**
          La classe Request est une classe qui va rassembler toutes les valeurs des superglobales de PHP($_GET, $_POST, ...). Pour instancier un objet de cette classe, on doit passer par les arguments d'une fonction d'un controller, on ne peut pas écrire $request = new Request; car l'objet sera incomplet (ce qu'on fait ligne23)
          Cette tecnhique s'appelle l'INJECTION DE DEPENDANCE. Plusieurs classes de Symfony ne peuvent être utilisées que de cette façon :
          - Request
          - Repository
          - ...
          Pour accéder aux valeurs des superglobales, l'objet Request dispose de propriétés qui correspondent à chaque superglobale :
          $_GET         $request->query
          $_POST        $request->request
          $_FILES       $request->files
          ...           ...
          Chacune de ces propriétés est un objet qui a une fonction 'get' qui permet d'accéder à une valeur en particulier.

          La classe Request permet aussi d'avoir des informations concernant la requête HTTP en cours.
          Par exemple, pour savoir quelle méthode HTTP est utilisée lors de la requête,
          $request->isMethod("POST")
         */

        // dump($request); // la fonction dump() est la version Symfony de var_dump
        // dd($request); // la fonction dd() exécute dump suivi de 'die' (dd égale dump and die)
        // dans la réponse visible sur le navigateur, les objets précédés de + sont publics, ceux précédes de # sont protégés, et ceux précédés de - sont privés. Dans l'objet request, apparaît dans un array tout ce qui est récupéré du formulaire après avoir été validé en cliquant sur Enregistrer
        if( $request->isMethod("POST")) {
            // Récupération des données du formulaire
            $titre = $request->request->get("titre");
            $resume = $request->request->get("resume");
            /** 
             On instancie un objet d'une classe Entity (ici, Livre). C'est à partir de cet objet qu'on va pouvoir enregistrer en bdd
             */
            $livre = new Livre;
            $livre->setTitre($titre);
            $livre->setResume($resume);
            /** 
             J'enregistre les données dans la table livre avec un objet de la classe LivreRepository.
             Les objets des classes Repository vont permettre d'exécuter des requêtes SQL sur la table correspondant à la classe.
             La méthode 'save' permet de faire les requêtes INSERT INTO et UPDATE. 
                Le 1er argument est un objet Entity,
                Le 2e argument doit être égal à true pour que la requête soit vraiment exécutée (sinon elle est mise en attente)
            */
            $livreRepository->save($livre, true);

            // On redirige vers la route qui affiche la liste des livres
            return $this->redirectToRoute("app_livre");
        }
        return $this->render('livre/form.html.twig');
    }

    #[Route('/livre/modifier/{id}', name: 'app_livre_modifier', requirements: ["id" => "\d+"])]
    public function edit(int $id, LivreRepository $lr, Request $rq)
    {
        $livre = $lr->find($id);
        $form = $this->createForm(FormLivreType::class, $livre);
        /**
         La méthode 'handleRequest' permet  à la variable $form de gérer les informations de la requête HTTP grâce à l'objet Request passé en argument.
         */
        $form->handleRequest($rq);
        if( $form->isSubmitted() && $form->isValid() ) {
            // est-ce qu'un fichier a été uploadé ?
            $fichier = $form->get('couverture')->getData();
            if($fichier ) {
                // récupération du nom du fichier uploadé
                $fileName = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slug = new AsciiSlugger();
                $newFileName = $slug->slug($fileName); // retourne un echaîne qui ne contient aucun caractère spécial, seulement des caractères acceptés dans une URL (pas d'espace, d'accent, ...)

                // Ajout d'une string unique pour éviter d'avoir plusieurs fichiers avec le même nom (le .= signifie que l'on fait une concaténation, ici avec le _ et un id unique)
                $newFileName .= "_" . uniqid();

                // Ajout de l'extension :
                $newFileName .= "." . $fichier->guessExtension();

                // Copie du fichier uploadé dans un dossier qui doit exister dans le dossier 'public' (il faut créer ce dossier, il n'est pas généré pas Symfony)
                $fichier->move("images", $newFileName);

                $livre->setCouverture($newFileName);
            }
            $lr->save($livre, true);
            return $this->redirectToRoute("app_livre");
        }
        return $this->render("livre/modifier.html.twig", [ "formLivre" => $form->createView() ]);
    }

    #[Route('/livre/supprimer/{id}', name: 'app_livre_supprimer', requirements: ["id" => "\d+"])]
    public function del(Livre $livre, LivreRepository $lr, Request $rq) {
        if($rq->isMethod("POST")) {
            $lr->remove($livre, true);
            return $this->redirectToRoute("app_livre");
        }
        return $this->render("livre/confirmation_suppression.html.twig", [
            "livre" => $livre
        ]);
    }
}
