<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise/show", name="app_show_entreprise")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findAll();

        return $this->render('entreprise/show.html.twig', compact('entreprises'));
    }

    /**
     * @Route("/entreprise/add", name="app_add_entreprise")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {

        $entreprise = new Entreprise;

        $form = $this->createForm(EntrepriseType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            $this->addFlash('info', 'l\'entreprise a été ajoutée');
            $entreprise = $form->getData();
            $entreprise->setBydefault(0);
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('app_add_entreprise');
        }

        return $this->render('entreprise/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/entreprise/delete/{id<[0-9]+>}", name="app_delete_entreprise")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(EntityManagerInterface $em, Entreprise $entreprise, EntrepriseRepository $entrepriseRepository, ExperienceRepository $experienceRepository)
    {
        $entrepriseId = $entrepriseRepository->findBy(['id' => $entreprise->getId()])[0]->getId();
        //dd($entrepriseId);
        $experience = $experienceRepository->findBy(['entreprise' => $entrepriseId]);
        //dd($experience);
        if ($experience) {
            $experience[0]->setEntreprise(null);
            $em->persist($experience[0]);
        }
        $em->remove($entreprise);
        $em->flush();
        return $this->redirectToRoute('app_show_entreprise');
    }

    /**
     * @Route("/entreprise/modification/{id<[0-9]+>}", name="app_modification_entreprise")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modify(Request $request, Entreprise $entreprise, EntityManagerInterface $em)
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('app_show_entreprise');
        }

        return $this->render('entreprise/modify.html.twig',[
            'form' => $form->createView(),
            'entreprise' => $entreprise
        ]);
    }
}
