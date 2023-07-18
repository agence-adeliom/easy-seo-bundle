<?php

declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Form\Fields;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TextareaCounterType extends TextareaType
{
    public function getBlockPrefix(): string
    {
        return 'textarea_counter';
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }

    public function getWidgetPrefix(): string
    {
        return 'textarea_counter';
    }
}
