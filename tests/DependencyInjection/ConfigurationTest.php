<?php

namespace Adeliom\EasySeoBundle\Tests\DependencyInjection;

use Adeliom\EasySeoBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testConfigurationProvidesDefaults(): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), [[]]);

        self::assertSame('%kernel.debug%', $config['enable_profiler']);
        self::assertSame(['^/admin*'], $config['ignore_profiler']);
        self::assertSame('|', $config['title']['separator']);
        self::assertSame('', $config['title']['suffix']);
        self::assertSame('breadcrumb', $config['breadcrumbs']['class']);
        self::assertSame('breadcrumb-item', $config['breadcrumbs']['item_class']);
        self::assertSame('', $config['breadcrumbs']['link_class']);
        self::assertSame('active', $config['breadcrumbs']['current_class']);
        self::assertSame('>', $config['breadcrumbs']['separator']);
        self::assertSame('breadcrumb-separator', $config['breadcrumbs']['separator_class']);
    }

    public function testConfigurationAcceptsOverrides(): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), [[
            'enable_profiler' => false,
            'ignore_profiler' => ['^/preview'],
            'title' => [
                'separator' => '/',
                'suffix' => 'Adeliom',
            ],
            'breadcrumbs' => [
                'class' => 'crumbs',
                'item_class' => 'crumb-item',
                'link_class' => 'crumb-link',
                'current_class' => 'is-current',
                'separator' => '/',
                'separator_class' => 'crumb-separator',
            ],
        ]]);

        self::assertFalse($config['enable_profiler']);
        self::assertSame(['^/preview'], $config['ignore_profiler']);
        self::assertSame('/', $config['title']['separator']);
        self::assertSame('Adeliom', $config['title']['suffix']);
        self::assertSame('crumbs', $config['breadcrumbs']['class']);
        self::assertSame('crumb-item', $config['breadcrumbs']['item_class']);
        self::assertSame('crumb-link', $config['breadcrumbs']['link_class']);
        self::assertSame('is-current', $config['breadcrumbs']['current_class']);
        self::assertSame('/', $config['breadcrumbs']['separator']);
        self::assertSame('crumb-separator', $config['breadcrumbs']['separator_class']);
    }
}
