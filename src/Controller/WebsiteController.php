<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Entity\Website;
use App\Form\Type\WebsiteType;
use App\Repository\WebsiteRepository;

class WebsiteController extends AbstractController
{
    /**
     * @Route("/website", name="website", methods={"GET"})
     */
    public function index(): Response
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $repository = $this->getDoctrine()->getRepository(Website::class);
        $websites = $repository->findAllJoinedToCountry();

        return $this->json([
            'message' => 'Success',
            'data' => $serializer->serialize($websites, 'json'),
        ]);
    }

    public function new(Request $request): Response
    {
        $website = new Website();

        $form = $this->createForm(WebsiteType::class, $website);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $website = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($website);
            $entityManager->flush();

            return $this->json(['message' => 'Success']);
        }

        return $this->json(['error' => $form->getErrors()]);
    }
}
