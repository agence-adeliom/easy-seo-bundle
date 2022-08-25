<?php

namespace Adeliom\EasySeoBundle;

use Adeliom\EasySeoBundle\DependencyInjection\EasySeoExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasySeoBundle extends Bundle
{
    /**
     * @return ExtensionInterface|null The container extension
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new EasySeoExtension();
    }
}
