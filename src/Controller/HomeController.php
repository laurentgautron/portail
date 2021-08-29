<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\SearchType;
use App\Repository\ExperienceRepository;
use App\Repository\UserCompetencesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $usersChanged = [];
        $usersNotChanged =[];
        foreach($users as $user) {
            if($user->getUpdatedAt() > $user->getLastLog()) {
            $usersChanged[] = $user;
            } else {
            $usersNotChanged[] = $user;
            }
        }

        return $this->render('home/index.html.twig', [
            'usersNotChanged' => $usersNotChanged,
            'usersChanged' => $usersChanged
        ]);
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
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder, SluggerInterface $slugger): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        //$form->remove('currentRole');
        //dd($form);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $documentFile = $form->get('document')->getData();

            if ($documentFile) {
                $originalFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$documentFile->guessExtension();

                try {
                    $documentFile->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setDocumentFilename($newFilename);
            }
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));
            $user->setRoles($form->get('role')->getData());
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
     * @Route("/user/{id<[0-9]+>}/edit", name="app_user_edit", methods={"GET", "POST"})
     * 
     */
    public function edit(Request $request, User $user, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
    
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($form->get('role')->getData());
            $user->setUpdatedAt(new \DateTimeImmutable());
            $em->flush($user);
            $this->addFlash('success', 'profil modifié avec succés');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/modification.html.twig', [
            'user' => $user,
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/user/{id<[0-9]+>}/delete", name="app_user_delete", methods={"GET"})
     * 
     * 
     */
    public function delete(User $user, EntityManagerInterface $em, ExperienceRepository $experienceRepository, UserCompetencesRepository $userCompetencesRepository): Response
    {
        $experiences = $experienceRepository->findByUser($user->getId());
        $userCompetences = $userCompetencesRepository->findByUser($user->getId());
        foreach($experiences as $experience) {
            $em->remove($experience);
            }
            foreach($userCompetences as $userCompetence) {
            $em->remove($userCompetence);
            }
        $em->remove($user);
        $em->flush();
        $this->addFlash('info', 'profil supprimé avec succés');

        return $this->redirectToRoute('app_home');   
    }

    /**
     * @Route("/document/new", name="app_document_new")
     */
    public function newDoc(Request $request)
    {
        $user = new User;
        $form = $this->createForm(ProductType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            // ... persist the $product variable or any other work

            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search/name", name="app_search_name")
     */
    public function researchByName(Request $request, UserRepository $userRepository)
    {
        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);
        //dd($form);
        $users = [];
        if ($form->isSubmitted() and $form->isValid()) {
            $users = $userRepository->findByNom($form->getData('nom'));
            //dd($users);
        }
        //$users = $userRepository->findByUser();
        return $this->render('search/user.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }
}
