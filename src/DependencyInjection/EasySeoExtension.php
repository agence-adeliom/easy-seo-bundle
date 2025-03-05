<?php

namespace Adeliom\EasySeoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EasySeoExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $container->setParameter('easy_seo.'.$key, $value);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->prependExtensionConfig('easy_seo', $config);
        $twigConfig = [];
        $twigConfig['paths'][__DIR__.'/../templates'] = 'easy_seo';
        $twigConfig['globals']['easy_seo'] = [];
        foreach ($config as $k => $v) {
            $twigConfig['globals']['easy_seo'][$k] = $v;
        }

        $container->prependExtensionConfig('twig', $twigConfig);
    }

    public function getAlias(): string
    {
        return 'easy_seo';
    }
}
