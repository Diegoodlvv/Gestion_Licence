<?php

namespace App\Form;

use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ModuleRepository;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code - champ obligatoire',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'Code du module'
                ],
                'label_attr' => ['class' => 'block text-sm font-medium text-slate-700 mb-2'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom - champ obligatoire',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'Nom du module'
                ],
                'label_attr' => ['class' => 'block text-sm font-medium text-slate-700 mb-2'],
            ])
            ->add('hoursCount', IntegerType::class, [
                'label' => 'Nombre d\'heures',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => '0'
                ],
                'label_attr' => ['class' => 'block text-sm font-medium text-slate-700 mb-2'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description - champ obligatoire',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                    'rows' => 4,
                    'placeholder' => 'Description du module'
                ],
                'label_attr' => ['class' => 'block text-sm font-medium text-slate-700 mb-2'],
            ])
            ->add('capstoneProject', CheckboxType::class, [
                'label' => 'Module effectué sur le projet fil rouge',
                'required' => false,
                'attr' => [
                    'class' => 'w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2'
                ],
                'label_attr' => ['class' => 'ml-2 text-base font-normal text-slate-700'],
            ])
        ;

        // fonction s'executant avant la recuperation des donnees
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $module = $event->getData(); // recupere l'entite module passe dans le controller
            $form = $event->getForm(); // recupere le formulaire lui meme pour modifier le champ

            $teachingBlock = $module->getTeachingBlock(); // recupere les info du TB lie au module pour les combiner
            $value = $teachingBlock->getCode() . ' - ' . $teachingBlock->getName();

            $form->add('teaching_block', TextType::class, [ // creation du formulaire basique
                'mapped' => false,
                'disabled' => true,
                'data' => $value,
                'label' => 'Bloc enseignement',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg bg-slate-100 text-slate-500 cursor-not-allowed'
                ],
                'label_attr' => ['class' => 'block text-sm font-medium text-slate-700 mb-2'],
            ]);
        });


        // contrainte pour que la liste des parent dans l'ajout ne soit que ceux des TB
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $module = $event->getData();
            $form = $event->getForm();

            $teachingBlock = $module->getTeachingBlock();

            $form->add('parent', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'name',
                'label' => 'Parent',
                'required' => false,
                'placeholder' => 'Sélectionnez un parent',
                // use = comme des parametre optionels
                // Paramètres = c'est les données qui arrivent au moment de l'exécution (quand Symfony construit le formulaire).
                // Use: c'est les données qui existent déjà autour au moment où on ecrit le code (dans le $builder).
                'query_builder' => function (ModuleRepository $mr) use ($teachingBlock, $module) {
                    return $mr->getTeachingBlockbyParent($teachingBlock, $module);
                },
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
                ],
                'label_attr' => ['class' => 'block text-sm font-medium text-slate-700 mb-2'],
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
