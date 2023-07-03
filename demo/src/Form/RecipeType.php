<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use PhpParser\Parser\Multiple;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                    'autocomplete'=> "off"
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'

                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('time' , IntegerType::class, [
                'label' => 'Temps en minutes',
                'attr' => [
                    'class' => 'form-control' ,
                    'min' => 1 ,
                    'max'=>1440 
                    
                ],
                'required'=> false ,
                'label_attr' => [
                    'class' => 'form-label mt-4'

                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\LessThan(1441),
                    new \Symfony\Component\Validator\Constraints\Positive()
                ]
            ])
            ->add('nbPeople' , IntegerType::class, [
                'label' => 'nombbre de persones',
                'attr' => [
                    'class' => 'form-control' ,
                    'min' => 1 ,
                    'max'=>50
                ],
                'required'=> false,
                'label_attr' => [
                    'class' => 'form-label mt-4'

                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\LessThan(51),
                    new \Symfony\Component\Validator\Constraints\Positive()
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'label' => 'difficulty',
                'attr' => [
                    'class' => 'form-range' ,
                    'min' => 1 ,
                    'max'=>5
                ],
                'required'=> false,
                'label_attr' => [
                    'class' => 'form-label mt-4'

                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\LessThan(6),
                    new \Symfony\Component\Validator\Constraints\Positive()
                ]
            ])
            ->add('description' , TextareaType::class ,[
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Length(['min' => 2,'max' => 301])
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete'=> "off"
                ],
                'required'=> false,
                'label' => 'Prix :',
                'label_attr' => [
                    'class' => 'form-label mt-4'

                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\LessThan(1001),
                    new \Symfony\Component\Validator\Constraints\Positive()
                ]
            ])
            ->add('isFavorite' , CheckboxType::class , [
             'label' => 'Favorit ?',
                'attr' => [
                    'class' => 'form-check-input m-5 ms-0'
                ],
                'required'=> false,
                'label_attr' => [
                    'class' => 'form-check-label m-5 ms-0 '
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotNull()
                ]
            ])
            ->add('ingredients' , EntityType::class , [
                 'class' => Ingredient::class , 

                 'query_builder' => function (IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                    ->orderBy('i.nom', 'ASC');
                },
                 'choice_label' => 'nom',
                 'label' => 'Ingredients ',
                 'label_attr' => [
                     'class' => 'form-label my-4'
                 ],
                 'attr' => [
                    'class' => 'form-control'
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'm-3'];
                },
                 'multiple'=> true,
                 'expanded'=> true,
                 ])
            
            ->add('submit' , SubmitType::class , [
                'attr' => [
                    'class' => 'btn btn-primary m-5'
                ],
                'label' => 'Créer ma recette',
            ])
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
