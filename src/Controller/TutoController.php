<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//  rajout 
use App\Entity\Tuto;
use Doctrine\ORM\EntityManagerInterface;


final class TutoController extends AbstractController
//  route tuto qui contient mes formation tuto
{
    #[Route('/tuto/{id}', name: 'app_tuto')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        //  aller chercher le product avec id
        $product = $entityManager->getRepository(Tuto::class)->find($id);
        //  si produit n'existe pas , un msg d'erreur s'affichera
        if(!$product){
            throw $this->createNotFoundException(
                'Aucun produit trouvé avec cette id'.$id
            );
        }
        //  si produit trouvé 
        return $this->render('tuto/index.html.twig', [
            'controller_name' => 'TutoController',
            //  je veux récup son nom 
            'name'=> $product->getName()
        ]);
    }
    //  route pour ajouter un tuto
    #[Route('/add-tuto', name: 'app_tuto_add')]
    public function createTuto(EntityManagerInterface $entityManager): Response
    {
        //  on doit tout mettre parce que tout nos données ne sont pas null 
        $product = new Tuto();
        $product->setName('Unity');
        $product->setSlug('tuto-unity');
        $product->setSubtitle('tuto-unity');
        $product->setDescription('Lorem ipsum dolar sit amet ');
        $product->setImage('unity.png');
        $product->setVideo('B6eqb-1IoQw');
        $product->setLink('https://www.formation-facile.fr/cours/unity3D');

        // tell Doctrine you want to  save the Product (no queries yet)
        $entityManager->persist($product);

        // eexcuter le requette 
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
}

