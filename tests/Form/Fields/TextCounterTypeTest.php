<?php

namespace Adeliom\EasySeoBundle\Tests\Form\Fields;

use Adeliom\EasySeoBundle\Form\Fields\TextCounterType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[CoversClass(\Adeliom\EasySeoBundle\Form\Fields\TextCounterType::class)]
final class TextCounterTypeTest extends TestCase
{
    public function testTypeExtendsTextTypeWithDedicatedPrefixes(): void
    {
        $type = new TextCounterType();

        self::assertSame(TextType::class, $type->getParent());
        self::assertSame('text_counter', $type->getBlockPrefix());
        self::assertSame('text_counter', $type->getWidgetPrefix());
    }
}
