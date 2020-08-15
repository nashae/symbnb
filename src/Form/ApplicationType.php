<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApplicationType extends AbstractType
{
    /**
     * configuration de base des champs
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}