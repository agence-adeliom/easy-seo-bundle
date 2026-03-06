<?php

namespace Adeliom\EasySeoBundle\Tests\Form\Fields;

use Adeliom\EasySeoBundle\Form\Fields\TextareaCounterType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[CoversClass(\Adeliom\EasySeoBundle\Form\Fields\TextareaCounterType::class)]
final class TextareaCounterTypeTest extends TestCase
{
    public function testTypeExtendsTextareaTypeWithDedicatedPrefixes(): void
    {
        $type = new TextareaCounterType();

        self::assertSame(TextareaType::class, $type->getParent());
        self::assertSame('textarea_counter', $type->getBlockPrefix());
        self::assertSame('textarea_counter', $type->getWidgetPrefix());
    }
}
