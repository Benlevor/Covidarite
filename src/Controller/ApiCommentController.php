<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HTTpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\CategoryRepository;

class ApiCommentController extends AbstractController
{
    /**
     * @Route("/api/comment", name="api_comment_index", methods={"GET","OPTIONS"})
     */
    public function index($id, Request $request, AnnonceRepository $annonceRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {

        $jsonReceived = $request -> getContent();
        $annonce = $annonceRepository -> find($id);

        try {

            $Comment = $serializer -> deserialize($jsonReceived, Comment::class, 'json');
            $Comment -> setAnnonce($annonce);
            

            $errors = $validator -> validate($Comment);

            if(count($errors) > 0) {
                $response = new JsonResponse();
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);
    
                return $response;
            }

            $em -> persist($Comment);
            $em -> flush();

            $response = new JsonResponse();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $content = $serializer->serialize($Comment, 'json', [
                'circular_reference_handler' => function ($newComment) {
                    return $newComment->getId();
                }
            ]);
    
            $response->setContent($content);
    
            return $response;

        } catch(NotEncodableValueException $e) {

            $response = new JsonResponse(['status' => 400, 'message' => $e->getMessage()]);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

            return $response;
        }
        
    }
}
