<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sorties",name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/createsortie", name="createsortie")
     */
    public function createsortie(Request $request): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class,$sortie);
        //todo traiter le formulaire
        return $this->render('sortie/createSortie.html.twig', [
            'sortieForm'=> $sortieForm->createView()

        ]);
    }


    /**
     * @Route("/afficher-sortie/{id}", name="afficher-sortie")
     */
        public function afficherSortie(int $id): Response
        {
            return $this->render('sortie/afficherSortie.html.twig',[

            ]);
        }


    /**
     * @Route("/modifier-sortie/{id}", name="modifier-sortie")
     */
    public function modifierSortie(int $id): Response
    {
        return $this->render('sortie/modifierSortie.html.twig',[

        ]);
    }


    /**
     * @Route("/annuler-sortie/{id}", name="annuler-sortie")
     */
    public function annulerSortie(int $id): Response
    {
        return $this->render('sortie/annulerSortie.html.twig',[

        ]);
    }

}