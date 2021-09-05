<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/show", name="app_show_category")
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryType::class, null, ['category' => false]);
        

        $form->add('modification', SubmitType::class, [
            'label' => 'Modifier',
        ]);

        $form->add('suppression', SubmitType::class, [
            'label'  => 'Supprimer'
        ]);

        $form->add('bydefault', SubmitType::class, [
            'label'  => 'choisr par défaut'
        ]);
        
        $form->handleRequest($request);
    

        if ($form->isSubmitted() and $form->isValid()) {
            $category = $categoryRepository->findByNom($form->getData()->getNom())[0];
            if ($form->get('suppression')->isClicked() and $form->get('nom')->getData()->getBydefault()) {
                $this->addFlash('info', 'c\'est la catégorie par défaut, vous devez la changer avant de la supprimer');
            } elseif ($form->get('suppression')->isClicked()) {
                return $this->redirectToRoute('app_delete_category', ['id' => $category->getId()]);
            } elseif ($form->get('bydefault')->isClicked()) {
                $byDefaultCategory = $categoryRepository->findByBydefault(1)[0];
                $byDefaultCategory->setBydefault(0);
                $category->setBydefault(1);
                $em->persist($byDefaultCategory);
                $em->persist($category);
                $em->flush();
            }
            else {
                return $this->redirectToRoute('app_modification_category', ['id' => $category->getId()]);
            }
        }
        return $this->render('category/show.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/delete/{id<[0-9]+>}", name="app_delete_category")
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function delete(Category $category, EntityManagerInterface $em, CompetencesRepository $competencesRepository, CategoryRepository $categoryRepository)
    {   
        $competences = $competencesRepository->findByCategory($category->getId());
        $byDefaultId = $categoryRepository->findByBydefault(1)[0];
        foreach($competences as $competence) {
            $competence->setCategory($byDefaultId);
            $em->persist($competence);
            $em->flush();
        }
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_show_category');
    }

    /**
     * @Route("/category/modify/{id<[0-9]+>}", name="app_modification_category")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modify(Request $request, Category $category, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryType::class, $category, ['category' =>true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_show_category');
        }

        return $this->render('category/modify.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/add", name="app_add_category")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, null, ['category' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $category = $form->getData();
            $category->setBydefault(0);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_show_category');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
