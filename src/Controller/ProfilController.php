<?php

namespace App\Controller;

use App\Form\MonProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{

    /**
     * @Route("/profil/modif", name="profilModif")
     */
    public function monProfil(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $participant = $this ->getUser();
        $monProfilForm = $this->createForm(MonProfilType::class, $participant);
        $monProfilForm->handleRequest($request);

        if ($monProfilForm->isSubmitted() && $monProfilForm->isValid()){

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('sucess', 'Informations mises à jour');
            $participant = $monProfilForm->getData();
        }

        return $this -> render('Profil/profil.html.twig', [
            'monProfilForm' => $monProfilForm->createView()
        ]);
    }

    /**
     * @Route("/profil/{idParticipant}", name="profildetail")
     */
    public function detailParticipant(int $idParticipant, ParticipantRepository $participantRepository) : Response
    {
        $participants = $participantRepository->find($idParticipant);
        return $this->render('Profil/detailProfil.html.twig', ["participants"=>$participants]);
    }

}
