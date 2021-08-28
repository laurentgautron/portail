<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }

    /**
     * @Route("/entreprise/add", name="app_add_entreprise")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {

        $entreprise = new Entreprise;

        $form = $this->createForm(EntrepriseType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            $entreprise = $form->getData();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('app_add_entreprise');
        }

        return $this->render('entreprise/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
