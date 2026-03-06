<?php

namespace Adeliom\EasySeoBundle\Tests;

use Adeliom\EasySeoBundle\DependencyInjection\EasySeoExtension;
use Adeliom\EasySeoBundle\EasySeoBundle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Adeliom\EasySeoBundle\EasySeoBundle::class)]
final class EasySeoBundleTest extends TestCase
{
    public function testBundleCreatesItsContainerExtension(): void
    {
        $bundle = new EasySeoBundle();

        self::assertInstanceOf(EasySeoExtension::class, $bundle->getContainerExtension());
    }
}
