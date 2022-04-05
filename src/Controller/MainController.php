<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Repository\CampusRepository;
use App\Repository\FiltreSortieRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController

{
    /**
     * @Route("/",name="main_home")
     */
    public function list(SortieRepository $sortieRepository, CampusRepository $campusRepository, Request $request):Response
    {
        $campus = $campusRepository ->findAll();
        $idCampus = $request->get('idcampus');
        $datedebut = $request->get('datedebut');
        $datefin = $request->get('datefin');
        $organisateur = $request->get('organisateur') == 'on';
        $inscrit = $request->get ('inscrit') == 'on';
        $nom = $request->get('recherche');

        $etat = new etat;
        $etat->getLibelle();


        $sorties = $sortieRepository->sortieFiltre($nom, $idCampus, $datedebut, $datefin, $organisateur, $inscrit);
        return $this->render('main/home.html.twig',[
            "campus" => $campus,
            "sorties" => $sorties,
            "etat" =>$etat,
            "recherche" =>$nom,
            "idcampus" =>$idCampus,
            "datedebut" =>$datedebut,
            "datefin" =>$datefin,
            "organisateur" =>$organisateur,
            "inscrit" =>$inscrit,
        ]);
    }



}