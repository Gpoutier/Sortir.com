<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\MotifAnulationType;
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
     * @Route("/afficher-sortie/{idSortie}", name="afficher-sortie")
     */
        public function afficherSortie(int $idSortie, SortieRepository $sortieRepository): Response
        {
            $sortie= $sortieRepository->find($idSortie);
            // s'il n'existe pas en bdd, on déclenche une erreur 404
            if (!$sortie){
                throw $this->createNotFoundException('This sortie do not exists! Sorry!');
            }

            return $this->render('sortie/afficherSortie.html.twig',[
                "sortie"=>$sortie
            ]);
        }


    /**
     * @Route("/modifier-sortie/{idSortie}", name="modifier-sortie")
     */
    public function modifierSortie(int $idSortie): Response
    {
        return $this->render('sortie/modifierSortie.html.twig',[

        ]);
    }


    /**
     * @Route("/annuler-sortie/{idSortie}", name="annuler-sortie")
     */
    public function annulerSortie(int $idSortie, EntityManagerInterface $entityManager,Request $request,EtatRepository $etatRepository,SortieRepository $sortieRepository): Response
    {

        $sortie = $sortieRepository->find($idSortie);
        $this->denyAccessUnlessGranted('POST_DELETE',$sortie);


        $motifForm=$this->createForm(MotifAnulationType::class);
        $motifForm->handleRequest($request);
        if ($motifForm->isSubmitted() && $motifForm->isValid()){
            //Récupération et stockage de la raison d'annulation dans la BDD
            $motif = $motifForm->getData();
            $motifFinal = $sortie->getInfosSortie() . "- Raison d'annulation : " . $motif["motif"];

            $sortie->setInfosSortie($motifFinal);


            //Changement  l'état
            $sortie->setEtat($etatRepository->find(4));


            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie Annulée !');

            return $this->redirectToRoute('main_home');
        }


        return $this->render('sortie/annulerSortie.html.twig',[
            "sortie"=>$sortie,
            "motifForm"=>$motifForm->createView()

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

        $this->addFlash('succes', "Vous êtes inscrit !");

        return $this->redirectToRoute('sortie_afficher-sortie',
            ["idSortie" => $idSortie]);
    }
    /**
     * @Route("/{idSortie}/desincription", name="desincription")
     */
    public function desinscrire(EntityManagerInterface $entityManager,int $idSortie, SortieRepository $sortieRepository)
    {
        $sortie = $sortieRepository->find($idSortie);
        /** @var  Participant $participant */
        $participant = $this ->getUser();
        $sortie->removeParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('succes', "Vous vous êtes désinscrit !");
        return $this->redirectToRoute('main_home');
    }


}