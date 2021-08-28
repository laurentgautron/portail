<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/category/show", name="app_show_category")
     */
    public function show(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/show.html.twig', compact('categories'));
    }

    /**
     * @Route("/category/delete/{id<[0-9]+>}", name="app_delete_category")
     */
    public function delete(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_show_category');
    }

    /**
     * @Route("/category/modify/{id<[0-9]+>}", name="app_modification_category")
     */
    public function modify(Request $request, Category $category, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryType::class, $category);

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
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_show_category');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}