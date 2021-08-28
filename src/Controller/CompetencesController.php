<?php

namespace App\Controller;

use App\Form\SearchType;
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

    // /**
    //  * @Route("/search/competences", name="app_search_competences")
    //  */
    // public function researchByName(Request $request, CompetencesRepository $competencesRepository, UserCompetencesRepository $usercompetencesRepository)
    // {
    //     $form = $this->createForm(SearchType::class);

    //     $form->handleRequest($request);
    //     $competences = [];
    //     $users = []; 
    //     if ($form->isSubmitted() and $form->isValid()) {
    //         $competences = $competencesRepository->findBynomcompetence($form->getData('nomcompetence'));
    //         foreach($competences as $competence) {
    //             $userCompetences = $usercompetencesRepository->searchComp($competence->getId());
    //             foreach($userCompetences as $userCompetence){
    //                 $users[] = $userCompetence->getUser();
    //             }
    //             //dd($users[0]->getId());
    //         }
    //     }
    //     return $this->render('search/competences.html.twig', [
    //         'form' => $form->createView(),
    //         'competences' => $competences,
    //         'users' => $users
    //     ]);
    // }

    /**
     * @Route("/search/competences", name="app_search_competences")
     */
    public function researchByCompNiv(Request $request, CompetencesRepository $competencesRepository, UserCompetencesRepository $usercompetencesRepository)
    {
        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);
        $competences = [];
        $users = []; 
        if ($form->isSubmitted() and $form->isValid()) {
            $competences = $competencesRepository->findBynomcompetence($form->getData('nomcompetence'));
            $niv = $form->get('niveau')->getData();
            //dd($niv == null);
            foreach($competences as $competence) {
                if ($niv == null) {
                    $userCompetences = $usercompetencesRepository->searchComp($competence->getId());
                } else {
                    $userCompetences = $usercompetencesRepository->searchCompNiv($competence->getId(), $niv);
                }
                foreach($userCompetences as $userCompetence){
                    $users[] = $userCompetence->getUser();
                }
                //dd($users);
            }
        }
        return $this->render('search/compNiveau.html.twig', [
            'form' => $form->createView(),
            'competences' => $competences,
            'users' => $users
        ]);
    }
}
