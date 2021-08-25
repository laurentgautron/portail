<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fonction')
            ->add('lieu')
            ->add('beginAt', null , [
                'years' => range(date('Y')-100, date('Y'))
            ])
            ->add('stopAt', null , [
                'years' => range(date('Y')-100, date('Y'))
            ])
            ->add('description')
            ->add('contexte')
            ->add('realisation')
            ->add('technique')
            ->add('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
