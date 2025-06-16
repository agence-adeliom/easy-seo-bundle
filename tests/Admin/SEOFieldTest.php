<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Admin;

use Adeliom\EasySeoBundle\Admin\Field\SEOField;
use Adeliom\EasySeoBundle\Form\SeoType;
use PHPUnit\Framework\TestCase;

final class SEOFieldTest extends TestCase
{
    public function testNew(): void
    {
        $field = SEOField::new('seo', 'label');
        $dto = $field->getAsDto();

        self::assertSame('seo', $dto->getProperty());
        self::assertSame('label', $dto->getLabel());
        self::assertSame('@EasySeo/seo-detail.html.twig', $dto->getTemplatePath());
        self::assertSame(SeoType::class, $dto->getFormType());
        self::assertSame('field-seo', trim($dto->getCssClass()));

        $assets = $dto->getAssets()->getCssAssets();
        $paths = array_map(static fn($asset) => $asset->getValue(), $assets);
        self::assertContains('bundles/easyseo/seo-form.css', $paths);
    }
}
