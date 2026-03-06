<?php

namespace Adeliom\EasySeoBundle\Tests\Services;

use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(\Adeliom\EasySeoBundle\Services\BreadcrumbCollection::class)]
final class BreadcrumbCollectionTest extends TestCase
{
    public function testCollectionStoresSimpleAndGeneratedItems(): void
    {
        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator
            ->expects(self::exactly(2))
            ->method('generate')
            ->willReturnMap([
                ['page_show', ['slug' => 'home'], UrlGeneratorInterface::ABSOLUTE_PATH, '/page/home'],
                ['seo_show', [], UrlGeneratorInterface::ABSOLUTE_PATH, '/seo'],
            ]);

        $collection = new BreadcrumbCollection();
        $collection->setGenerator($generator);
        $object = new \stdClass();

        $collection->addSimpleItem('Homepage');
        $collection->addRouteItem('Page', [
            'route' => 'page_show',
            'params' => ['slug' => 'home'],
        ], $object);
        $collection->addRouteItem('SEO', [
            'route' => 'seo_show',
        ]);

        self::assertSame([
            ['linkName' => 'Homepage', 'target' => null, 'object' => null],
            ['linkName' => 'Page', 'target' => '/page/home', 'object' => $object],
            ['linkName' => 'SEO', 'target' => '/seo', 'object' => null],
        ], $collection->getItems());
    }
}
