<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        $annonce= new Annonce();

        $form= $this->createFormBuilder($annonce)
                    ->add('title', TextType::class,[
                        'attr'=>[
                            'placeholder'=> "Titre de l'annonce",
                        ]
                    ])
                    ->add('content',TextareaType::class,[
                        'attr'=>[
                            'placeholder'=>"Veuillez saisir le contenu de votre annonce"
                        ]
                    ])
                    ->add('image',TextType::class,[
                        'attr'=>[
                            'placeholder'=> "URL de l'image"
                        ]
                    ])

                    ->add('type',TextType::class,[
                        'attr'=>[
                            'placeholder'=> "Type d'utilisateur"
                        ]
                    ])
                    ->getForm();

        return $this->render('covid/create.html.twig', [
            'formAnnonce'=> $form->createView()
        ]);
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
