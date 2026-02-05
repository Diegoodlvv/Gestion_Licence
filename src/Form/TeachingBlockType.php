<?php

namespace App\Form;

use App\Entity\TeachingBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeachingBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code du bloc - champ obligatoire',
                'disabled' => true,
                'attr' => ['class' => 'w-full bg-[#DFE4EA] px-4 py-3 border-slate-200 rounded-md bg-gray-50 text-slate-500', 'readonly' => true,],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du bloc - champ obligatoire',
                'disabled' => true,
                'attr' => ['class' => 'w-full bg-[#DFE4EA] text- px-4 py-3  border-slate-200 rounded-md bg-gray-50 text-slate-500', 'readonly' => true,],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('hours_count', NumberType::class, [
                'label' => 'Nombre d\'heures - champ obligatoire',
                'attr' => ['class' => 'w-full px-4 py-3 border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500'],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description - champ obligatoire',
                'attr' => ['class' => 'w-full px-4 py-3 border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500', 'rows' => 5],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeachingBlock::class,
        ]);
    }
}
