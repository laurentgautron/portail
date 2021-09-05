<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\SearchType;
use App\Entity\Competences;
use App\Form\CompetencesType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompetencesRepository;
use App\Repository\UserCompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CompetencesController extends AbstractController
{
    /**
     * @Route("/competences/add", name="app_add_competences")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {

        $competence = new Competences;

        $form = $this->createForm(CompetencesType::class);
        $form->add('category', EntityType::class, [
            'label' => 'categorie',
            'class' => Category::class,
            'query_builder' => function (CategoryRepository $cat) {
                return $cat->createQueryBuilder('cat')
                    ->orderBy('cat.bydefault', 'DESC');
                },
            ]);

        $form->add('ajout', SubmitType::class, [
            'label' => 'ajouter'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            $this->addFlash('info', 'nom de compétence ajouté !');
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        $competences = $competencesRepository->findAll();

        return $this->render('competences/show.html.twig', compact('competences'));
    }

    /**
     * @Route("/competences/delete/{id<[0-9]+>}", name="app_delete_competence")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function modification(Request $request, Competences $competence, EntityManagerInterface $em)
    {
        $form = $this->createForm(CompetencesType::class, $competence);
        $form->add('submit', SubmitType::class, [
            'label' => 'modifier'
        ]);

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

    /** 
    * @Route("/research/comp", name="app_search_competences")
    *@Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COM')")
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

    /** 
    * @Route("/research/app", name="app_search_appetences")
    *@Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COM')")
    */
    public function researchByCompAp(Request $request, CompetencesRepository $competencesRepository, UserCompetencesRepository $usercompetencesRepository)
    {
        $form = $this->createForm(SearchType::class);
        $form->remove('niveau');
        $form->add('appetences', IntegerType::class, [
            'label' => 'Note appétence'
        ]);
        $form->add('submit', SubmitType::class,[
            'label' => 'Enregistrer'
        ]);

        $form->handleRequest($request);
        $competences = [];
        $users = [];

        if ($form->isSubmitted() and $form->isValid()) {
            $competences = $competencesRepository->findBynomcompetence($form->getData('nomcompetence'));
            $app = $form->get('appetences')->getData();
            foreach($competences as $competence) {
                $userCompetences = $usercompetencesRepository->searchCompAp($competence->getId(), $app);
                foreach($userCompetences as $userCompetence){
                    $users[] = $userCompetence->getUser();
                }
            }
        }
        return $this->render('search/compApp.html.twig', [
            'form' => $form->createView(),
            'competences' => $competences,
            'users' => $users
        ]);
    }
}
