<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function tableauSortie(SortieRepository $sortieRepository, Request $request, EntityManagerInterface $entityManager, ): Response
    {
        $sorties = $sortieRepository->findAll();

        $inscription = $request->headers->get('referer');
        //$sorties = $this->getRepository(sortie::class)->findall();
        return $this->render('main/home.html.twig', [
            'sortie' => $sortieRepository->findAll()
        ]);
    }
}
