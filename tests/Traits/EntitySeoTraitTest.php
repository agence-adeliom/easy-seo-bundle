<?php

namespace Adeliom\EasySeoBundle\Tests\Traits;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Traits\EntitySeoTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasySeoBundle\Traits\EntitySeoTrait::class)]
final class EntitySeoTraitTest extends TestCase
{
    public function testTraitInitializesAndReplacesEmbeddedSeoObject(): void
    {
        $entity = new EntityWithSeo();

        self::assertInstanceOf(SEO::class, $entity->getSEO());

        $seo = new SEO();
        $seo->title = 'Replacement';
        $entity->setSEO($seo);

        self::assertSame($seo, $entity->getSEO());
    }
}

final class EntityWithSeo
{
    use EntitySeoTrait;
}
