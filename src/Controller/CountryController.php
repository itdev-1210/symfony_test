<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Country;
use App\Form\Type\CountryType;

class CountryController extends AbstractController
{
    /**
     * @Route("/country", name="country")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CountryController.php',
        ]);
    }

    public function new(Request $request): Response
    {
        $country = new Country();

        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $country = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->json(['message' => 'Success']);
        }

        return $this->json(['error' => $form->getErrors()]);
    }
}
