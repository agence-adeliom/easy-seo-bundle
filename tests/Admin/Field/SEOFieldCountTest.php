<?php

namespace Adeliom\EasySeoBundle\Tests\Admin\Field;

use Adeliom\EasySeoBundle\Admin\Field\SEOFieldCount;
use Adeliom\EasySeoBundle\Form\SeoCountType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasySeoBundle\Admin\Field\SEOFieldCount::class)]
final class SEOFieldCountTest extends TestCase
{
    public function testFieldConfiguresExpectedEasyAdminDto(): void
    {
        $field = SEOFieldCount::new('seo', 'SEO Count');
        $dto = $field->getAsDto();

        self::assertSame('seo', $dto->getProperty());
        self::assertSame('SEO Count', $dto->getLabel());
        self::assertSame(SeoCountType::class, $dto->getFormType());
        self::assertSame('@EasySeo/seo-detail.html.twig', $dto->getTemplatePath());
        self::assertStringContainsString('field-seo', $dto->getCssClass());
        self::assertSame('', $dto->getDefaultColumns());
        self::assertFalse($dto->getDisplayedOn()->has(Crud::PAGE_INDEX));
        self::assertCount(1, $dto->getAssets()->getCssAssets());
    }
}
