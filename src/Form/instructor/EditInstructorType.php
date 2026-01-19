<?php

namespace App\Form;

use App\Entity\Instructor;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditInstructorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'property_path' => 'user.email',
                'label' => 'Email',
                'attr' => ['class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black', 'placeholder' => 'exemple@email.com'],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('firstname', TextType::class, [
                'property_path' => 'user.firstname',
                'label' => 'Prénom',
                'attr' => ['class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black'],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('lastname', TextType::class, [
                'property_path' => 'user.lastname',
                'label' => 'Nom',
                'attr' => ['class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black'],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'data-controller' => 'tom-select'
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instructor::class,
        ]);
    }
}
