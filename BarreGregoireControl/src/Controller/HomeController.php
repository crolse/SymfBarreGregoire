<?php
// id
//reference 20
//nom 50
//EAN 13 ou 14 et empecher de rentrer une valeur fausse
namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index()
    {

        //je vais chercher le repository l'equivalent du dbset en c#
        $repository=$this->getDoctrine()->getRepository(Produit::class);



        $produit = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'produit' => $produit
        ]);
    }


    /**
     * @Route("/produit/ajouter", name="produit_ajouter")
     */
    public function ajouter(Request $request){

        $produit=new Produit(); // a voir


        $formulaire=$this->createForm(ProduitType::class, $produit);


        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()){

            $em=$this->getDoctrine()->getManager();


            $em->persist($produit);
            $em->flush();


            return $this->redirectToRoute("produit");
        }


        return $this->render('home/formulaire.html.twig',[
            "formulaire"=>$formulaire->createView()
            ,"h1"=>"Ajouter un Produit"
        ]);

    }




    /**
     * @Route("/produit/modifier/{id}", name="produit_modifier")
     */
    public function modifier(Request $request, $id){

        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produit=$repository->find($id);


        $formulaire=$this->createForm(ProduitType::class, $produit);


        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()){

            $em=$this->getDoctrine()->getManager();

            //je dis au manager de garder cet objet en BDD
            $em->persist($produit);
            $em->flush();

            //je m'en vais
            return $this->redirectToRoute("produit");
        }


        return $this->render('home/formulaire.html.twig',[
            "formulaire"=>$formulaire->createView()
            ,"h1"=>"modifier un produit".$produit->getNom()
        ]);

    }




    /**
     * @Route("/produit/supprimer/{id}", name="produit_supprimer")
     */

    public function supprimer(Request $request, $id)
    {
        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produit=$repository->find($id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        $produits=$repository->findAll();


        return $this->render('home/index.html.twig', [
            'produits' => $produits
        ]);
    }










}
