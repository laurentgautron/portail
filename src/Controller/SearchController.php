<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    // /**
    //  * @Route("/search", name="app_search")
    //  */
    // public function research(Request $request, UserRepository $userRepository)
    // {
    //     $form = $this->createForm(SearchType::class);
    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()) {
    //         $criteres = $form->getData();
    //         $users = $userRepository->searchUser($criteres);
    //     }

    //     return $this->render('search/user.html.twig', [
    //         'formResearch' => $form->createView()
    //     ]);
    // }
}