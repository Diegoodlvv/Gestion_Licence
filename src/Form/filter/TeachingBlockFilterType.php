<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeachingBlockFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisissez le code',
                    'class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all',
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700 mb-1',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du bloc',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisissez le nom',
                    'class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all',
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700 mb-1',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'validation_groups' => ['filter'],
        ]);
    }
}
