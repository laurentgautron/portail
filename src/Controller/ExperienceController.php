<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Experience;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExperienceController extends AbstractController
{
    /**
     * @Route("/experience", name="experience")
     */
    public function index(): Response
    {
        return $this->render('experience/index.html.twig', [
            'controller_name' => 'ExperienceController',
        ]);
    }

    /**
     * @Route("/experience/{id<[0-9]+>}/create", name="app_experience_create")
     */
    public function create(Request $request, EntityManagerInterface $em, User $user):Response
    {
        $experience = new Experience;
        $experience->setUser($user);
        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($experience);
            $em->flush($experience);

            $this->addFlash('success', 'expérience crée avec succés');
            $url = '/user'.'/'.$user->getId();
            return $this->redirect($url);
        }
        
        return $this->render('experience/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/experience/{id<[0-9]+>}/delete", name="app_experience_delete", methods={"GET"})
     * 
     */
    public function delete(Experience $experience, EntityManagerInterface $em): Response
    {
        $userId = $experience->getUser()->getId();
        $em->remove($experience);
        $em->flush();
        $this->addFlash('info', 'experience supprimée avec succés');
        $url = '/user'.'/'.$userId;

        return $this->redirect($url);   
    }
}
