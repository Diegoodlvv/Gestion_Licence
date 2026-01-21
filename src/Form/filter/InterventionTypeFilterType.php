<?php

namespace App\Form;

use App\Entity\InterventionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionTypeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => false,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Saisissez le nom',
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'validation_groups' => ['filter']
        ]);
    }
}
