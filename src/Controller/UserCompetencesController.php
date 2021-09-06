<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserCompetences;
use App\Form\UserCompetencesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserCompetencesController extends AbstractController
{
    /**
     * @Route("/user/{id<[0-9]+>}/add/competence", name="app_user_add_competence")
     */
    public function addCompetence(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $userCompetence = new UserCompetences;

        $form = $this->createForm(UserCompetencesType::class);
        $form->add('Ajouter', SubmitType::class, [
            'label' => 'Ajouter'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $userCompetence = $form->getData();
            $userCompetence->setUser($user);
            $em->persist($userCompetence);
            $em->flush();
            
            $url = '/user'.'/'.$user->getId();
            return $this->redirect($url); 
        }
        //dd($user->getId());
        return $this->render('user_competences/addCompetences.html.twig', [
            'addUserCompetenceForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id<[0-9]+>}/{user<[0-9]+>}/modify", name="app_competences_modify")
     */
    public function modify(Request $request, UserCompetences $userCompetence, EntityManagerInterface $em)
    {
        //dd($userCompetence);
        $form = $this->createForm(UserCompetencesType::class, $userCompetence);
        $form->add('Ajouter', SubmitType::class, [
            'label' => 'modifier'
        ]);
        
        $form->remove('competence');

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($userCompetence);
            $em->flush();
            return $this->redirectToRoute('app_user_show', ['id' => $userCompetence->getUser()->getId()]);
        }

        return $this->render('user_competences/modify.html.twig', [
            'form' => $form->createView(),
            'nomCompetence' => $userCompetence->getCompetence()->getNomcompetence()
        ]);
    }
}
