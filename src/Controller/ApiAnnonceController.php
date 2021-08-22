<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiAnnonceController extends AbstractController
{
    /**
     * @Route("/api/annonce", name="api_annonce")
     */
    public function index(): Response
    {
        return $this->render('api_annonce/index.html.twig', [
            'controller_name' => 'ApiAnnonceController',
        ]);
    }
}
