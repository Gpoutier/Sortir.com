<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function createsortie(
        Request $request,
        EntityManagerInterface $entityManager,

        EtatRepository $etatRepository
    ): Response

    {
        /** @var Participant $participant */
        $participant = $this->getUser();
        $etatOpen =  $etatRepository-> findOneBy(['libelle'=>'Ouvert']);
        $etatCreation =  $etatRepository-> findOneBy(['libelle'=>'En création']);

        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class,$sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){


            $sortie->setEtat($etatOpen);
            $sortie->setEtat($etatCreation);
            $sortie ->setOrganisateur($participant);
            $sortie ->setCampus($participant->getCampus());
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie est bien enregistré.');
            return $this->redirectToRoute('main_home',['id' => $sortie->getIdSortie()]);
        }

        //todo afficher les infos lieu avec l'APi

        return $this->render('sortie/createSortie.html.twig', [
            'sortieForm'=> $sortieForm->createView(),
            'participant'=> $participant

        ]);
    }


    /**
     * @Route("/afficher-sortie/{id}", name="afficherSortie")
     */
        public function afficherSortie(int $id, SortieRepository $sortieRepository): Response
        {
            $sortie= $sortieRepository->find($id);
            // s'il n'existe pas en bdd, on déclenche une erreur 404
            if (!$sortie){
                throw $this->createNotFoundException('This sortie do not exists! Sorry!');
            }

            return $this->render('sortie/afficherSortie.html.twig',[
                "sortie"=>$sortie
            ]);
        }


    /**
     * @Route("/modifier-sortie/{id}", name="modifierSortie")
     */
    public function modifierSortie(int $id): Response
    {
        return $this->render('sortie/modifierSortie.html.twig',[

        ]);
    }


    /**
     * @Route("/annuler-sortie/{id}", name="annulerSortie")
     */
    public function annulerSortie(int $id): Response
    {
        return $this->render('sortie/annulerSortie.html.twig',[

        ]);
    }

    /**
     * @Route("/list-sortie", name="sortie-list")
     */
    public function list(SortieRepository $sortieRepository):Response
    {
        $sorties = $sortieRepository->findAll();
        return $this->render('sortie/list.html.twig',[
            "sorties"=>$sorties
        ]);
    }

}