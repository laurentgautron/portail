<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Form\CompetencesType;
use App\Entity\UserCompetences;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompetencesRepository;
use App\Repository\UserCompetencesRepository;
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

    /**
     * @Route("/competences/show", name="app_show_competences")
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        $competences = $competencesRepository->findAll();

        return $this->render('competences/show.html.twig', compact('competences'));
    }

    /**
     * @Route("/competences/delete/{id<[0-9]+>}", name="app_delete_competence")
     */
    public function delete(Competences $competence, EntityManagerInterface $em, UserCompetencesRepository $usercompetencesRepository): Response
    {
        $uc = $usercompetencesRepository->searchComp($competence->getId());
        //dd($uc);
        foreach ($uc as $row) {
            $em->remove($row);
        }
        $em->remove($competence);
        $em->flush();

        return $this->redirectToRoute('app_show_competences');
    }

    /**
     * @Route("/competences/modification/{id<[0-9]+>}", name="app_modification_competence")
     */
    public function modification(Request $request, Competences $competence, EntityManagerInterface $em)
    {
        $form = $this->createForm(CompetencesType::class, $competence);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($competence);
            $em->flush();

            return $this->redirectToRoute('app_show_competences');
        }
        

        return $this->render('competences/modify.html.twig', [
            'form' => $form->createView(),
            'competence' => $competence
        ]);
    }
}
