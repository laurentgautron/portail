<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserCompetences;
use App\Form\UserCompetencesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}
