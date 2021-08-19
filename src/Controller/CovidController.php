<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/covid/{id}/edit", name="covid_edit")
     */
    public function form(Annonce $annonce= null, Request $request, EntityManagerInterface $manager) {

        if(!$annonce){
            $annonce= new Annonce();
        }

        $form= $this->createFormBuilder($annonce)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->add('type')
                    ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$annonce->getID()){
                $annonce->setCreatedAt(new \DateTimeImmutable());
            }

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('covid_show',['id'=>$annonce->getID()]);

        }

        return $this->render('covid/create.html.twig', [
            'formAnnonce'=> $form->createView(),
            'editMode'=>$annonce ->getID() !== null
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
