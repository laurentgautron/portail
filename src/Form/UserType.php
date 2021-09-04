<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'required'        => true,
                'first_options'   => [
                    'label' => 'Mot de passe',
                    'attr'  => ['placeholder' => 'Merci de saisir votre mot de passe.']
                ],
                'second_options'  => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr'  => ['placeholder' => 'Merci de confirmer votre mot de passe.']
                ]
            ])
            ->add('telephone')
            ->add('document', FileType::class, [
                'label' => 'Document (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $entity = $event->getData();
                $form1 = $event->getForm();
                $form1->add('role', ChoiceType::class, [
                        'mapped' => false,
                        'label' => 'rôle',
                        'choices' => [
                            '' => '',
                            'Admin' => 'ROLE_ADMIN',
                            'Collaborateur' => 'ROLE_COLL',
                            'commercial' => 'ROLE_COM',
                            'Candidat' => 'ROLE_CAND'
                        ],
                        //'expanded' => true,
                        'multiple' => false,
                        'data' => $entity->getRoles()? $entity->getRoles()[0]:'',
                    ]);
                })
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
