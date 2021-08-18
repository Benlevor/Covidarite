<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;

class CovidController extends AbstractController
{
    /**
     * @Route("/covid", name="covid")
     */
    public function index(AnnonceRepository $repo): Response
    {
        $annonces = $repo->findAll();

        return $this->render('covid/index.html.twig', [
            'controller_name' => 'CovidController',
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this ->render('covid/home.html.twig', [
            'title' => "Bienvenue sur le site de solidarité contre le Covid"
        ]);
    }

    /**
     * @Route("/covid/new", name="covid_create")
     */
    public function create() {
        return $this->render('covid/create.html.twig');
    }
    
    /**
     * @Route("/covid/{id}", name="covid_show")
     */
    public function show(Annonce $annonce){

        return $this->render('covid/show.html.twig',[
            'annonce'=> $annonce
        ]);
    }

}
