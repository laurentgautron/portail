<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Documents;
use App\Form\DocumentType;
use App\Repository\DocumentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentController extends AbstractController
{
    /**
     * @Route("/document/{id}/create", name="app_document_create")
     */
    public function create(Request $request, EntityManagerInterface $em, User $user, SluggerInterface $slugger): Response
    {
        $document = new Documents;

        $form = $this->createForm(DocumentType::class, $document);

        $form->add('submit', SubmitType::class, [
            'label' => 'Enregisrter'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $documentFile = $form->get('documentName')->getData();
            //dd($documentFile);

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

                $document->setName($newFilename);
                $em->persist($document);
            }
            $document->setUser($user);
            $em->flush();
        }

        return $this->render('document/index.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }
}
