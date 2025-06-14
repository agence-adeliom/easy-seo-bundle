<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\DataCollector;

use Adeliom\EasySeoBundle\DataCollector\SeoCollector;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SeoCollectorTest extends TestCase
{
    public function testCollectParsesHtml(): void
    {
        $breadcrumb = new BreadcrumbCollection();
        $collector = new SeoCollector($breadcrumb, [], true, []);

        $html = '<html><head><title>My Title</title>' .
            '<meta name="description" content="Short desc" />' .
            '<meta name="keywords" content="k1" />' .
            '<meta name="robots" content="index" />' .
            '<meta name="page-key" content="key" />' .
            '<link rel="canonical" href="https://example.com" />' .
            '<meta property="og:image" content="image.jpg" />' .
            '</head><body></body></html>';

        $request = new Request([], [], ['_route' => 'home']);
        $response = new Response($html);

        $collector->collect($request, $response);

        self::assertSame('My Title', (string) $collector->getTitle()['value']);
        self::assertSame('Short desc', (string) $collector->getDescription()['value']);
        self::assertTrue(isset($collector->keywords));
        self::assertTrue(isset($collector->robots));
        self::assertTrue(isset($collector->pageKey));
        self::assertTrue(isset($collector->canonical));
        self::assertTrue(isset($collector->cover));
    }
}
