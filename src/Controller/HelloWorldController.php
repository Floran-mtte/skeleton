<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    /**
     * Cette méthode retourne la vue hello_world/index.html.twig
     * @Route("/hello-world",name="hello_world")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('hello_world/index.html.twig', ['prenom' => 'Floran', 'nom' => 'Maitte', 'cours' => ['seance' => 1]]);
    }

    /**
     * @Route("/produit/{id}", name="product_detail")
     */
    public function detailProduit($id) {
        return $this->render('hello_world/detail.html.twig', ['id_product' => $id]);
    }

}
