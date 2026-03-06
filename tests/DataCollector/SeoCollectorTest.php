<?php

namespace Adeliom\EasySeoBundle\Tests\DataCollector;

use Adeliom\EasySeoBundle\DataCollector\SeoCollector;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(\Adeliom\EasySeoBundle\DataCollector\SeoCollector::class)]
final class SeoCollectorTest extends TestCase
{
    public function testCollectorReturnsEarlyWhenDisabled(): void
    {
        $collector = new SeoCollector(new BreadcrumbCollection(), ['separator' => '|'], false);

        $collector->collect(Request::create('/seo'), new Response('<html></html>'));

        self::assertFalse($collector->enabled);
        self::assertFalse($collector->ignored);
        self::assertSame([], $collector->getTitle());
        self::assertSame([], $collector->getDescription());
    }

    public function testCollectorSkipsIgnoredRoutesAndPatterns(): void
    {
        $collector = new SeoCollector(new BreadcrumbCollection(), ['separator' => '|'], true, ['^/admin', 'ignored_route']);

        $patternRequest = Request::create('/admin/dashboard');
        $collector->collect($patternRequest, new Response('<html></html>'));
        self::assertTrue($collector->ignored);

        $collector->reset();

        $routeRequest = Request::create('/seo');
        $routeRequest->attributes->set('_route', 'ignored_route');
        $collector->collect($routeRequest, new Response('<html></html>'));

        self::assertTrue($collector->ignored);
    }

    public function testCollectorExtractsSeoDataAndExposesStatuses(): void
    {
        $breadcrumb = new BreadcrumbCollection();
        $breadcrumb->addSimpleItem('Homepage');

        $collector = new SeoCollector($breadcrumb, ['separator' => '|'], true);
        $request = Request::create('/seo');
        $request->attributes->set('_route', 'seo_show');

        $html = <<<'HTML'
<html>
    <head>
        <title>SEO title within the expected window size</title>
        <meta name="description" content="This description has the right length for the collector to mark it as healthy and keep the panel status green for the current page output.">
        <meta name="keywords" content="seo, tests">
        <meta name="robots" content="index,follow">
        <meta name="page-key" content="home">
        <link rel="canonical" href="https://example.test/seo">
        <meta property="og:image" content="https://example.test/cover.jpg">
    </head>
</html>
HTML;

        $collector->collect($request, new Response($html));

        self::assertSame('green', $collector->getTitle()['status']);
        self::assertSame('status-sucess', $collector->getTitle()['metric']);
        self::assertSame('green', $collector->getDescription()['status']);
        self::assertSame('status-sucess', $collector->getDescription()['metric']);
        self::assertTrue(isset($collector->keywords));
        self::assertTrue(isset($collector->robots));
        self::assertTrue(isset($collector->pageKey));
        self::assertTrue(isset($collector->canonical));
        self::assertTrue(isset($collector->cover));
        self::assertSame('seo, tests', $collector->keywords['value']->toString());
        self::assertSame('index,follow', $collector->robots['value']->toString());
        self::assertSame('home', $collector->pageKey['value']->toString());
        self::assertSame('https://example.test/seo', $collector->canonical['value']->toString());
        self::assertSame('https://example.test/cover.jpg', $collector->cover['value']->toString());
        self::assertSame([
            ['linkName' => 'Homepage', 'target' => null, 'object' => null],
        ], $collector->breadcrumb);
        self::assertSame(SeoCollector::class, $collector->getName());

        $collector->reset();

        self::assertFalse(isset($collector->keywords));
        self::assertSame([], $collector->getTitle());
    }

    public function testCollectorMarksEmptyAndShortContentAsErrorsOrWarnings(): void
    {
        $collector = new SeoCollector(new BreadcrumbCollection(), ['separator' => '|'], true);

        $collector->collect(Request::create('/empty'), new Response('<html><head><title></title><meta name="description" content=""></head></html>'));
        self::assertSame('red', $collector->getTitle()['status']);
        self::assertSame('status-error', $collector->getTitle()['metric']);
        self::assertSame('red', $collector->getDescription()['status']);
        self::assertSame('status-error', $collector->getDescription()['metric']);

        $collector->reset();

        $collector->collect(Request::create('/short'), new Response('<html><head><title>short title</title><meta name="description" content="too short"></head></html>'));
        self::assertSame('yellow', $collector->getTitle()['status']);
        self::assertSame('status-warning', $collector->getTitle()['metric']);
        self::assertSame('yellow', $collector->getDescription()['status']);
        self::assertSame('status-warning', $collector->getDescription()['metric']);
    }
}
