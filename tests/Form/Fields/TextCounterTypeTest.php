<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Form\Fields;

use Adeliom\EasySeoBundle\Form\Fields\TextCounterType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TextCounterTypeTest extends TestCase
{
    public function testProperties(): void
    {
        $type = new TextCounterType();

        self::assertSame('text_counter', $type->getBlockPrefix());
        self::assertSame(TextType::class, $type->getParent());
        self::assertSame('text_counter', $type->getWidgetPrefix());
    }
}
