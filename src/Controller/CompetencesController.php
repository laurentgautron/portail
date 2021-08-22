<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Form\CompetencesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetencesController extends AbstractController
{
    /**
     * @Route("/competences/add", name="app_add_competences")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {

        $competence = new Competences;

        $form = $this->createForm(CompetencesType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            $competence = $form->getData();
            $em->persist($competence);
            $em->flush();

            return $this->redirectToRoute('app_add_competences');
        }

        return $this->render('competences/add.html.twig', [
            'formCompetences' => $form->createView()
        ]);
    }
}
