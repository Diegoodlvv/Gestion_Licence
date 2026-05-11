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
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du bloc - champ obligatoire',
                'disabled' => true,
            ])
            ->add('hours_count', NumberType::class, [
                'label' => 'Nombre d\'heures - champ obligatoire',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description - champ obligatoire',
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
