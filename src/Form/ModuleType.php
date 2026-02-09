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
                    'placeholder' => 'Code du module'
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom - champ obligatoire',
                'attr' => [
                    'placeholder' => 'Nom du module'
                ],
            ])
            ->add('hoursCount', IntegerType::class, [
                'label' => 'Nombre d\'heures',
                'attr' => [
                    'placeholder' => '0'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description - champ obligatoire',
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Description du module'
                ],
            ])
            ->add('capstoneProject', CheckboxType::class, [
                'label' => 'Module effectué sur le projet fil rouge',
                'required' => false,
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
                'label' => 'Bloc enseignement'
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
                // Paramètres = c'est les données qui arrivent au moment de l'exécution (quand Symfony construit le formulaire).
                // Use: c'est les données qui existent déjà autour au moment où on ecrit le code (dans le $builder).
                'query_builder' => function (ModuleRepository $mr) use ($teachingBlock, $module) {
                    return $mr->getTeachingBlockbyParent($teachingBlock, $module);
                }
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
