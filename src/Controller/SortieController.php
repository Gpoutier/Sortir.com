<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
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

        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class,$sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){


            $etat = $sortieForm->get('enregistrer')->isClicked()
                ? $etatRepository-> findOneBy(['libelle'=>'En création'])
                :$etatRepository-> findOneBy(['libelle'=>'Ouvert']);;

            $sortie ->setEtat($etat);
            $sortie ->setOrganisateur($participant);
            $sortie ->setCampus($participant->getCampus());
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie est bien enregistré.');
            return  $this->redirectToRoute('main_home');

        }

        //todo afficher les infos lieu avec l'APi

        return $this->render('sortie/createSortie.html.twig', [
            'sortieForm'=> $sortieForm->createView(),
            'participant'=> $participant,





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

    /**
     * @Route("/{idSortie}/inscription", name="inscription")
     */
    public function inscrire(EntityManagerInterface $entityManager,int $idSortie, SortieRepository $sortieRepository)
    {
        $sortie = $sortieRepository->find($idSortie);
        /** @var  Participant $participant */
        $participant = $this ->getUser();
        $sortie->addParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie detail',
            ["idSortie" => $idSortie]);
    }
    /**
     * @Route("/{idSortie}/desincription", name="desincription")
     */
    public function desinscrire(EntityManagerInterface $entityManager,int $idsortie, SortieRepository $sortieRepository)
    {
        $sortie = $sortieRepository->find($idsortie);
        /** @var  Participant $participant */
        $participant = $this ->getUser();
        $sortie->removeParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }

}

