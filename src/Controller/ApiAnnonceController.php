<?php

namespace App\Controller;

use App\Entity\Annonce;
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

class ApiAnnonceController extends AbstractController
{
    /**
     * @Route("/api/annonce", name="api_annonce_index", methods={"GET","OPTIONS"})
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);
        $response->headers->set('User-Agent','PostmanRuntime/7.28.3');
        $response->headers->set('Accept','*/*');


        $em = $this->getDoctrine()->getManager();
        $annonces = $em->getRepository(Annonce::class)->findAll();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $content = $serializer->serialize($annonces, 'json', [
            'circular_reference_handler' => function ($annonces) {
                return $annonces->getId();
            }
        ]);

        $response->setContent($content);

        return $response;

    }
    /**
     * @Route("/api/annonce", name="api_annonce_store ", methods={"POST"})
     */
    public function store(CategoryRepository $categoryRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $jsonReceived = $request->getContent();

        try {

            $annonce = $serializer->deserialize($jsonReceived, Annonce::class, 'json');

            $catValue = $annonce->getCategory();
            $catTitle = $catValue->getTitle();
            $category = $categoryRepository->findOneBy(['title' => $categoryTitle]);
            // setCategory à la nouvelle recette créée
            $annonce->setCategory($category);

            $em->persist($newRecipe);
            $em->flush();


            $response = new JsonResponse();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);
            $response->headers->set('User-Agent','PostmanRuntime/7.28.3');
            $response->headers->set('Accept','*/*');

            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $content = $serializer->serialize($annonce, 'json', [
                'circular_reference_handler' => function ($annonce) {
                    return $annonce->getId();
                }
            ]);
    
            $response->setContent($content);
    
            return $response;

        }

         catch (NotEncodableValueException $e) {
            
            $response = new JsonResponse(['status' => 400, 'message' => $e->getMessage()]);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

            return $response;
        }
    }

    /**
     * @Route("/api/annonce/{id}", name="api_annonce_delete", methods={"DELETE","OPTIONS"})
     */
    public function delete($id, AnnonceRepository $annonceRepository, EntityManagerInterface $em)
    {
        $Deleted = $annonceRepository->find($id);

        $em->remove($Deleted);
        $em->flush();

        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);
        $response->headers->set('User-Agent','PostmanRuntime/7.28.3');
        $response->headers->set('Accept','*/*');

        return $response;

    }

    /**
     * @Route("/api/annonce/{id}", name="api_annonce_edit", methods={"PUT","OPTIONS"})
     */

    public function put($id, Request $request, CategoryRepository $categoryRepository, RecipeRepository $recipeRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {


        $jsonReceived = $request->getContent();
        $Modified = $recipeRepository->find($id);

        try {
            $deserializedReceived = $serializer->deserialize($jsonReceived, Annonce::class, 'json');
            $categoryAnnonce = $deserializedReceived->getCategory();
            $categoryRepo = $categoryRepository->findOneBy(["title" => $categoryAnnonce->getTitle()]);
            // setCategory à la nouvelle recette créée
            $Modified->setTitle($deserializedReceived->getTitle());
            $Modified->setContent($deserializedReceived->getContent());
            $Modified->setCategory($categoryRepo);
            $Modified->setImage($deserializedReceived->getImage());
            $Modified->setnomComplet($deserializedReceived->getNomComplet());
            $Modified->setPhoneNumber($deserializedReceived->getPhoneNumber());
            $Modified->setemail($deserializedReceived->getEmail());
            $Modified->setzipcode($deserializedReceived->getZipcode());
            
            $errors = $validator->validate($deserializedReceived);

            // vérifier si le validator n'a pas d'erreurs
            if (count($errors) > 0) {

                $response = new JsonResponse();
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

                return $response;
            }

            $em->persist($Modified);
            $em->flush();

            $response = new JsonResponse();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

            return $response;

        } catch (NotEncodableValueException $e) {

            $response = new JsonResponse(['status' => 400, 'message' => $e->getMessage()]);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, DELETE, OPTIONS");
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', true);

            return $response;
        }
}
