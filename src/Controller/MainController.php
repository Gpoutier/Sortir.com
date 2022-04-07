<?php

namespace App\Controller;

use App\modele\FiltreSortie;
use App\Form\FiltreSortieType;
use App\Repository\CampusRepository;
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
    public function list(SortieRepository $sortieRepository, Request $request):Response
    {
        $filtre = new FiltreSortie();
        $filtre ->setCampus($this->getUser()->getCampus());
        $filtre ->setIduser($this->getUser()->getIdParticipant());
        $filtreSortieType = $this->createForm(FiltreSortieType::class, $filtre);
        $filtreSortieType->handleRequest($request);
        $sorties = $sortieRepository->sortieFiltre($filtre);

        return $this->render('main/home.html.twig',[
            'filtreSortieType' => $filtreSortieType->createView(),
            'sorties' => $sorties
        ]);
    }



}