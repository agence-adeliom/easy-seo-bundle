<?php

namespace Adeliom\EasyCommonBundle;

use Adeliom\EasyCommonBundle\DependencyInjection\EasyCommonExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasyCommonBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new EasyCommonExtension();
    }
}
