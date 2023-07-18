<?php

declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Form\Fields;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextCounterType extends TextType
{
    public function getBlockPrefix(): string
    {
        return 'text_counter';
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function getWidgetPrefix(): string
    {
        return 'text_counter';
    }
}
