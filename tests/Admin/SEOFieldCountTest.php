<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Admin;

use Adeliom\EasySeoBundle\Admin\Field\SEOFieldCount;
use Adeliom\EasySeoBundle\Form\SeoCountType;
use PHPUnit\Framework\TestCase;

final class SEOFieldCountTest extends TestCase
{
    public function testNew(): void
    {
        $field = SEOFieldCount::new('seo', 'label');
        $dto = $field->getAsDto();

        self::assertSame('seo', $dto->getProperty());
        self::assertSame('label', $dto->getLabel());
        self::assertSame('@EasySeo/seo-detail.html.twig', $dto->getTemplatePath());
        self::assertSame(SeoCountType::class, $dto->getFormType());
        self::assertSame('field-seo', trim($dto->getCssClass()));
    }
}
