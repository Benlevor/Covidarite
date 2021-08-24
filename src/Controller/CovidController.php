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
use App\Form\AnnonceType;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchType;

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
        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$annonce->getID()){
                $annonce->setCreatedAt(new \DateTimeImmutable());
            }

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('covid_show',['id'=>$annonce->getId()]);

        }

        return $this->render('covid/create.html.twig', [
            'formAnnonce'=> $form->createView(),
            'editMode'=>$annonce ->getID() !== null
        ]);
    }

    /**
     * @Route("/covid/newhelp", name="covid_createh")
     * @Route("/covid/{id}/edit", name="covid_edit")
     */
    public function formh(Annonce $annonce= null, Request $request, EntityManagerInterface $manager) {

        if(!$annonce){
            $annonce= new Annonce();
        }
        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$annonce->getID()){
                $annonce->setCreatedAt(new \DateTimeImmutable());
            }

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('covid_show',['id'=>$annonce->getId()]);

        }

        return $this->render('covid/createh.html.twig', [
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

    /**
    * @Route("/covid/{id}/comment", name="create_comment")
    */

    public function comment($id ,Request $request, EntityManagerInterface $manager, Annonce $annonce) {

        $repo = $this ->getDoctrine() ->getRepository(Annonce::class);
        $annonce = $repo ->find($id);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setAnnonce($annonce);

            $manager->persist($comment);
            $manager->flush();
        return $this->redirectToRoute('covid_show', ['id' => $annonce->getId()]);
        }
        return $this ->render('covid/comment.html.twig',[
            'formComment' => $form->createView(),
            'annonce'=> $annonce
      ]);
    
    }

    /**
     * @Route("annonces/{id}/delete", name="covid_delete")
     */

        public function delete($id, EntityManagerInterface $manager) {

            $repo = $this ->getDoctrine() ->getRepository(Annonce::class);
            $annonce = $repo ->find($id);
  
            $manager->remove($annonce);
            $manager->flush();
  
            return $this ->render('covid/delete.html.twig', [
              'annonce' => $annonce
            ]);

        }

    /**
    * @Route("/search",name="search")
    */

        public function search(Request $request) {

            $repo = $this ->getDoctrine()->getRepository(Annonce::class);
            $annonces = $repo -> findAll();
            $form = $this->createForm(SearchType::class);
    
            $form ->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()) {
    
              return $this->render('covid/search.html.twig', [
                'annonceResearch' => $request->request->get('search'), 
                'annonces' => $annonces
                ]);
    
            }
    
          return $this ->render('covid/search_form.html.twig',[
              'formResearch' => $form->createView()
        ]);
           }

}

