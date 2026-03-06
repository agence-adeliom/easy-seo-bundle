<?php

namespace Adeliom\EasySeoBundle\Tests\Entity;

use Adeliom\EasySeoBundle\Entity\SEO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasySeoBundle\Entity\SEO::class)]
final class SEOTest extends TestCase
{
    public function testToStringReturnsTitle(): void
    {
        $seo = new SEO();
        $seo->title = 'Homepage';

        self::assertSame('Homepage', (string) $seo);
    }

    public function testEntityStartsWithExpectedDefaults(): void
    {
        $seo = new SEO();

        self::assertNull($seo->description);
        self::assertTrue($seo->sitemap);
        self::assertSame([], $seo->robots);
    }
}
