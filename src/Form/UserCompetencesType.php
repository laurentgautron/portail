<?php

namespace App\Form;

use App\Entity\Competences;
use App\Entity\UserCompetences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UserCompetencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competence', EntityType::class, [
                'class' => Competences::class,
                'choice_label' => 'nomCompetence'
            ])
            ->add('niveau', IntegerType::class)
            ->add('appetence', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCompetences::class,
        ]);
    }
}
