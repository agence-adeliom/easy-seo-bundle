<?php

namespace Adeliom\EasySeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('easy_seo');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('enable_profiler')->defaultValue('%kernel.debug%')->end()
                ->arrayNode('ignore_profiler')
                    ->defaultValue([
                        '^/admin*'
                    ])->scalarPrototype()->end()
                ->end()
                ->arrayNode('title')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('separator')->defaultValue('|')->end()
                        ->scalarNode('suffix')->defaultValue('')->end()
                    ->end()
                ->end()
                ->arrayNode('breadcrumbs')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue('breadcrumb')->end()
                        ->scalarNode('item_class')->defaultValue('breadcrumb-item')->end()
                        ->scalarNode('link_class')->defaultValue('')->end()
                        ->scalarNode('current_class')->defaultValue('active')->end()
                        ->scalarNode('separator')->defaultValue('>')->end()
                        ->scalarNode('separator_class')->defaultValue('breadcrumb-separator')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
