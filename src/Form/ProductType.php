<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez votre catégorie',
                'mapped' => false,
                'required' => false,
            ])
            ->add('subCategory', ChoiceType::class, [
                'placeholder' => 'Sous Catégorie (Choisir une catégorie)',
                'label' => 'Sous Catégorie',
                'required' => false
            ]);
        $formModifier = function (FormInterface $form, Category $category = null) {
            $subCategory = null === $category ? [] : $category->getSubCategories();

            $form->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $subCategory,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Sous Catégorie  (Choisir une catégorie)',
                'attr' => ['class' => 'custom-select'],
                'label' => 'Sous Catégories'
            ]);
        };

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $category = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $category);
            }
        );


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
