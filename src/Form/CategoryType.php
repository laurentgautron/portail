<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $options['category'];
        if ($category) {
            $builder
                ->add('nom')
                ->add('submit', SubmitType::class, [
                    'label' => 'Enregistrer'
                ]);
        } else {
            $builder
                ->add('nom', EntityType::class, [
                        'label' => 'categorie',
                        'class' => Category::class,
                        'query_builder' => function (CategoryRepository $cat) {
                            return $cat->createQueryBuilder('cat')
                                ->orderBy('cat.bydefault', 'DESC');
                            },
                        ])
                ;
        }
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['category']);
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
