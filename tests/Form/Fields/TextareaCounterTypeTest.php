<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Form\Fields;

use Adeliom\EasySeoBundle\Form\Fields\TextareaCounterType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class TextareaCounterTypeTest extends TestCase
{
    public function testProperties(): void
    {
        $type = new TextareaCounterType();

        self::assertSame('textarea_counter', $type->getBlockPrefix());
        self::assertSame(TextareaType::class, $type->getParent());
        self::assertSame('textarea_counter', $type->getWidgetPrefix());
    }
}
