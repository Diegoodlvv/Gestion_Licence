<?php

namespace App\Form;

use App\Entity\InterventionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class InterventionTypeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black'],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('color', TextType::class, [
                'label' => 'Code couleur (hexadecimal) - champ obligatoire',
                'required' => true,
                'attr' => [
                    'placeholder' => '#6750A4',
                    'pattern' => '#[0-9A-Fa-f]{6}',
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
                'constraints' => [
                    new Regex(
                        pattern: '/^#[0-9A-Fa-f]{6}$/',
                        message: 'Le code couleur doit etre au format hexadecimal (#RRGGBB)'
                    ),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black'],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InterventionType::class,
        ]);
    }
}
