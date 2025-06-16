<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Traits;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Traits\EntitySeoTrait;
use PHPUnit\Framework\TestCase;

final class EntitySeoTraitTest extends TestCase
{
    private function getEntity(): object
    {
        return new class() {
            use EntitySeoTrait;
        };
    }

    public function testDefaultSeo(): void
    {
        $entity = $this->getEntity();
        self::assertInstanceOf(SEO::class, $entity->getSEO());
    }

    public function testSetSeo(): void
    {
        $entity = $this->getEntity();
        $seo = new SEO();
        $seo->title = 'Test';
        $entity->setSEO($seo);

        self::assertSame('Test', $entity->getSEO()->title);
    }
}
