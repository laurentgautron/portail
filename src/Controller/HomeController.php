<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('home/index.html.twig', compact('users'));
    }

    /**
     * @Route("/user/{id<[0-9]+>}", name="app_user_show", methods={"GET"})
     *
     */
    public function show(User $user): Response
    {
        return $this->render('home/show.html.twig', compact('user'));
    }


    /**
     * @Route("/user/create", name="app_user_create", methods={"GET", "POST"})
     *
     */
    public function create(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush($user);

            $this->addFlash('success', 'profil créé avec succés');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id<[0-9]+>}/mod", name="app_user_mod", methods={"GET", "POST"})
     * 
     */
    public function edit(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $em->flush($user);
            $this->addFlash('success', 'pin modifié avec succés');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/modification.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/user/{id<[0-9]+>}/delete", name="app_user_delete", methods={"GET"})
     * 
     * 
     */
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        $this->addFlash('info', 'profil supprimé avec succés');

        return $this->redirectToRoute('app_home');   
    }

    /**
     * @Route("/mod", name="app_mod", methods={"GET"})
     * 
     */
    public function modif():Response
    {
        return $this->render('home/mod.html.twig');
    }
}
