<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;



final class SigninController extends AbstractController
{
    #[Route('/signin', name: 'app_signin')]
    public function index(Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
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
            // $user= $form->getData();
            $existingUser = $em->getRepository(User::class)->findOneBy([
                'email' => $user->getEmail()
            ]);
            // si utilisateur existe déja avec le meme email
            if ($existingUser) {
                $this->addFlash('error', 'Un compte avec cet email existe déjà.');

            return $this->redirectToRoute('app_signin');
    }
            //  étape de hasher le PWD -> récup le MDP de notre user
            $plaintextPassword = $user->getPassword();
            // on utilise la fonction hashPassword
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            // ca me donne le nouveau MPD hashé 
            $user->setPassword($hashedPassword);
            // sauvegarde en BD

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('signin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
