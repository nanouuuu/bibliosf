<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_admin_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('admin/livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivreRepository $livreRepository): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        // est-ce qu'un fichier a été uploadé ?
                        $fichier = $form->get('couverture')->getData();
                        if( $fichier ) {
                            // récupération du nom du fichier uploadé
                            $fileName = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                            $slug = new AsciiSlugger();
                            $newFileName = $slug->slug($fileName); // retourne une chaîne qui ne contient que des caractères acceptés dans un URL (pas d'espace, d'accent, ...)
            
                            // ajout d'un string unique pour éviter d'avoir plusieurs fichiers avec le meme nom
                            $newFileName .= "_" . uniqid();
            
                            // ajout de l'extension
                            $newFileName .= "." . $fichier->guessExtension();
            
                            // copie du fichier uploadé dans un dossier, qui doit exister dans le dossier 'public'
                            $fichier->move("images", $newFileName);
            
                            $livre->setCouverture($newFileName);
                        }
            
            $livreRepository->save($livre, true);

            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('admin/livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, LivreRepository $livreRepository): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        // est-ce qu'un fichier a été uploadé ?
                        $fichier = $form->get('couverture')->getData();
                        if( $fichier ) {
                            // récupération du nom du fichier uploadé
                            $fileName = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                            $slug = new AsciiSlugger();
                            $newFileName = $slug->slug($fileName); // retourne une chaîne qui ne contient que des caractères acceptés dans un URL (pas d'espace, d'accent, ...)
            
                            // ajout d'un string unique pour éviter d'avoir plusieurs fichiers avec le même nom
                            $newFileName .= "_" . uniqid();
            
                            // ajout de l'extension
                            $newFileName .= "." . $fichier->guessExtension();
            
                            // copie du fichier uploadé dans un dossier, qui doit exister dans le dossier 'public'
                            $fichier->move("images", $newFileName);
            
                            $livre->setCouverture($newFileName);
                        }
            
            $livreRepository->save($livre, true);

            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, LivreRepository $livreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $livreRepository->remove($livre, true);
        }

        return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
