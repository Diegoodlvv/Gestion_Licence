<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class FormHelpers extends AbstractType
{
    public function getConfiguration(string $label, string $placeholder, array $options = []): array
    {
        // array_replace_recursive permet d'écraser certaines valeurs par défaut comme la classe attr pour teaching_block/parent)
        // tout en gardant le reste de la structure.
        return array_replace_recursive([
            'label' => $label,
            'attr' => [
                //'class' => 'w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                'placeholder' => $placeholder
            ],
            'label_attr' => [
                'class' => 'block text-sm font-medium text-slate-700 mb-2'
            ]
        ], $options);
    }
}
