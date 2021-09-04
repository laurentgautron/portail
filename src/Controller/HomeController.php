<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\CompetencesRepository;
use App\Repository\ExperienceRepository;
use App\Repository\LastconnexionRepository;
use App\Repository\LogoutRepository;
use App\Repository\UserCompetencesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    /**
     * 
     * @Route("/", name="app_home")
     * 
     */
    public function index(UserRepository $userRepository, LastconnexionRepository $lastconnexionRepository, UserCompetencesRepository $userCompetencesRepository, ExperienceRepository $experienceRepository): Response
    {
        $users = $userRepository->findAll();
        $usersUpdated =[];
        $collChanged = [];
        $candChanged = [];
        $newColl = [];
        $newCand = [];
        if ($this->getUser()) {
            $lastConnexionTab = $lastconnexionRepository->findByUser($this->getUser()->getId());
            //dd($this->getUser()->getUpdatedAt() > $lastConnexion);
            if ($lastConnexionTab == []) {
                $usersUpdated = $users;
            } else {
                $lastConnexion = $lastConnexionTab[0]->getLasLogoutAt();
                foreach($users as $user) {
                    //recheche des expéreinces changées ($user->getId(), $lastConnexion)
                    $experienceChanged = $experienceRepository->isExperienceChangedAfter($user->getId(), $lastConnexion);
                    $competenceChanged = $userCompetencesRepository->isCompetenceChangedAfter($user->getId(), $lastConnexion);
                    $new = ($user->getCreatedAt() == $user->getUpdatedAt());
                    if ($new and ($user->getRoles()[0] == "ROLE_COLL") and ($user->getUpdatedAt() > $lastConnexion)) {
                        $newColl[] = $user;
                    } elseif ($new and ($user->getRoles()[0] == "ROLE_CAND") and ($user->getUpdatedAt() > $lastConnexion))  {
                        $newCand[] = $user;
                    } elseif ($lastConnexion and !$new and (($user->getUpdatedAt() > $lastConnexion) or $competenceChanged or $experienceChanged)){
                        $usersUpdated[] = $user;
                    }
               }
            }

            foreach($usersUpdated as $userUpdated) {
                if ($userUpdated->getRoles()[0] == "ROLE_COLL") {
                    $collChanged[] = $userUpdated;
                } elseif ($userUpdated->getRoles()[0] == "ROLE_CAND") {
                    $candChanged[] = $userUpdated;
                }
            }
            //dd($collChanged);
            //dd($usersUpdated);

            
        }
        
        

        return $this->render('home/index.html.twig',[
            'users' => $users,
            'usersUpdated' => $usersUpdated,
            'collChanged' => $collChanged,
            'candChanged' => $candChanged,
            'newColl' => $newColl,
            'newCand' => $newCand
            ]);
    }

    /**
     * @Route("/user/{id<[0-9]+>}", name="app_user_show", methods={"GET"})
     *
     */
    public function show(User $user, UserCompetencesRepository $userCompetencesRepository, CompetencesRepository $competencesRepository, CategoryRepository $categoryRepository): Response
    {
        $todisplay = [];
        $competencesId = [];
        $competences = $userCompetencesRepository->findByUser($user->getId());
        foreach($competences as $competence) {
            $competencesId[] = $competence->getCompetence()->getId();
        }
        $categories = $categoryRepository->findAll();
        foreach($categories as $category) {
            $cat = $category->getId();
            foreach($competencesId as $competenceId) {
                $categoryIdForUser = $competencesRepository->findById($competenceId)[0]->getCategory()->getId();
                if ($categoryIdForUser == $cat) {
                    $todisplay[$cat][] = $competenceId;
                }
            }
        }
        $cates = [];
        $lesCompetencesUser = [];
        //dd($todisplay);
        foreach($todisplay as $key => $values) {
            //dd($key);
            //dd($values);
            $cates = $categoryRepository->findById($key)[0]->getNom();
            //dd($cates);
            //dd($values);
            foreach($values as $value) {
                //dd($value);
                    $nomComp = $competencesRepository->findById($value)[0]->getNomcompetence();
                    $niveau = $userCompetencesRepository->searchCompUser($value, $user)[0]->getNiveau();
                    $appetence = $userCompetencesRepository->searchCompUser($value, $user)[0]->getAppetence();
                    $idcomp = $userCompetencesRepository->searchCompUser($value, $user)[0]->getId();
                    //dd($niveau);
                    $lesCompetencesUser[$cates][] = [$nomComp, $niveau, $appetence, $idcomp];
                }
        }
        //dd($lesCompetencesUser);
        // comps recherche par user et colmpetencesID
        //dd($cates);
        //dd(array_keys($todisplay));
        return $this->render('home/show.html.twig', [
            'lescompetences' => $lesCompetencesUser,
            'user' => $user,
        ]);
    }


    /**
     * @Route("/user/create", name="app_user_create", methods={"GET", "POST"})
     *
     */
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder, SluggerInterface $slugger): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class, [
            'label' => 'Enregistrer'
        ]);

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
            $user->setAsleft(0);
            $em->persist($user);
            $em->flush($user);

            $this->addFlash('success', 'profil créé avec succés');

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
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
        $currentRole = $user->getRoles()[0];
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->add('modif', SubmitType::class, [
            'label' => 'Modifier'
        ]);
    
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if ($currentRole !== $form->get('role')->getData()) {
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setUpdatedAt(new \DateTimeImmutable());
            }
            if ($currentRole === "ROLE_COLL" and $form->get('role')->getData() === "ROLE_CAND") {
                $user->setAsLeft(1);
            }
            $user->setRoles($form->get('role')->getData());
            $em->persist($user);
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
        $form->remove('niveau');

        $form->handleRequest($request);
        $users = [];
        if ($form->isSubmitted() and $form->isValid()) {
            $users = $userRepository->findByNom($form->getData('nom'));
        }
        return $this->render('search/user.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }
}
