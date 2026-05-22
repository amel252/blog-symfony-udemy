<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class SigninController extends AbstractController
{
    #[Route('/signin', name: 'app_signin')]
    public function index(Request $req, EntityManagerInterface $em): Response
    {
        //  création d'un objet user vide qui sera rempli apres soumiss form
        $user = new User();

        //  création du formulaire
        $form= $this->createForm(SigninType::class, $user);
        // traitement de la requette
        $form->handleRequest($req);
        // Vérification formulaire soumis + valide
        if($form->isSubmitted() && $form->isValid()){
            // récup des données
            $user= $form->getData();
            // sauvegarde en BD

            $em->persist($user);
            $em->flush();
            
            // $this->addFlash('success', 'Inscription réussie !');
        }else {
            // $this->addFlash('error', 'Veuillez corriger les erreurs du formulaire.');
        }

        return $this->render('signin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
