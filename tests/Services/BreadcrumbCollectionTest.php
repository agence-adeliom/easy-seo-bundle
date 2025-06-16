<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Services;

use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class BreadcrumbCollectionTest extends TestCase
{
    public function testAddSimpleItem(): void
    {
        $collection = new BreadcrumbCollection();
        $collection->addSimpleItem('Home', '/');

        self::assertSame([
            ['linkName' => 'Home', 'target' => '/', 'object' => null],
        ], $collection->getItems());
    }

    public function testAddRouteItem(): void
    {
        $generator = new class() implements UrlGeneratorInterface {
            public function generate(
                string $name,
                array $parameters = [],
                int $referenceType = self::ABSOLUTE_PATH
            ): string {
                return '/' . $name;
            }

            public function setContext(\Symfony\Component\Routing\RequestContext $context): void {}
            public function getContext(): \Symfony\Component\Routing\RequestContext { return new \Symfony\Component\Routing\RequestContext(); }
        };

        $collection = new BreadcrumbCollection();
        $collection->setGenerator($generator);
        $collection->addRouteItem('Home', ['route' => 'homepage']);

        self::assertSame([
            ['linkName' => 'Home', 'target' => '/homepage', 'object' => null],
        ], $collection->getItems());
    }
}
