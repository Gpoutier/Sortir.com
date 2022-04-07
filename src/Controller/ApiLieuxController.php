<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route ("/api")
 */

class ApiLieuxController extends AbstractController
{

    /**
     * @Route ("/lieux/{idVille}", name="api_liste", methods={"GET"})
     */
   public function liste( VilleRepository $villeRepository, SerializerInterface $serializer, int $idVille)
   {
        $ville = $villeRepository->find($idVille);
        $lieux = $ville->getLieu();
     /**   //normalisation(transformation d'objets complexes en tableau associatif
        $lieuxNormalises = $normalizer->normalize($lieux,null,['groups'=>'liste_lieux']);

        //convertion en Json
        $json = json_encode($lieuxNormalises);
        return new Response($json, Response::HTTP_OK,['Content-Type'=>'application/json']);
      */

      /**   //méthode compressée, les deux étapes sont compilées
        $json = $serializer->serialize($lieux, 'json',['groups'=>'liste_lieux']);

        return new JsonResponse($json, Response::HTTP_OK,[],true);
      */
        //puis en une seule ligne
       return $this ->json($lieux,Response::HTTP_OK,[],['groups'=>'liste_lieux']);


   }

}