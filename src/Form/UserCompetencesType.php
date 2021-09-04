<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Competences;
use App\Entity\UserCompetences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserCompetencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'choice_label' => 'nom'
            // ])
            ->add('competence', EntityType::class, [
                'class' => Competences::class,
                'choice_label' => 'nomCompetence'
            ])
            ->add('niveau')
            ->add('appetence')
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCompetences::class,
        ]);
    }
}
