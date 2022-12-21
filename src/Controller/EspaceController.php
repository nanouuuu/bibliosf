<?php

namespace App\Controller;

use App\Repository\AbonneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted("ROLE_LECTEUR")]
#[Route("/espace-lecteur")]
class EspaceController extends AbstractController
{
    #[Route('/', name: 'app_espace')]
    public function index(): Response
    {
        /* Si on veut récupérer les informations de l'utilisateur connecté dans un contrôleur, on utilise $this->getUser().
        Si aucun utilisateur n'est connecté, getUser() renvoie NULL */
        $user = $this->getUser();
        return $this->render('espace/index.html.twig');
    }
}


