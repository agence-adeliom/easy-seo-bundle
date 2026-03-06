<?php

namespace Adeliom\EasySeoBundle\Tests\DependencyInjection;

use Adeliom\EasySeoBundle\DependencyInjection\EasySeoExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class EasySeoExtensionTest extends TestCase
{
    public function testExtensionLoadsParametersAndServices(): void
    {
        $container = new ContainerBuilder();
        $extension = new EasySeoExtension();

        $extension->load([[
            'enable_profiler' => false,
            'title' => [
                'separator' => '/',
                'suffix' => 'Adeliom',
            ],
        ]], $container);

        self::assertSame('easy_seo', $extension->getAlias());
        self::assertFalse($container->getParameter('easy_seo.enable_profiler'));
        self::assertSame(['^/admin*'], $container->getParameter('easy_seo.ignore_profiler'));
        self::assertSame([
            'separator' => '/',
            'suffix' => 'Adeliom',
        ], $container->getParameter('easy_seo.title'));
        self::assertTrue($container->hasDefinition('Adeliom\\EasySeoBundle\\Twig\\EasySeoExtension'));
        self::assertTrue($container->hasDefinition('easy_seo.breadcrumb'));
        self::assertTrue($container->hasDefinition('Adeliom\\EasySeoBundle\\Form\\SeoType'));
        self::assertTrue($container->hasDefinition('Adeliom\\EasySeoBundle\\DataCollector\\SeoCollector'));
        self::assertTrue($container->hasAlias('Adeliom\\EasySeoBundle\\Services\\BreadcrumbCollection'));
    }
}
