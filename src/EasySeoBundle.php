<?php

namespace Adeliom\EasySeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Adeliom\EasySeoBundle\DependencyInjection\EasySeoExtension;

class EasySeoBundle extends Bundle
{
    /**
     * @return ExtensionInterface|null The container extension
     */
    public function getContainerExtension()
    {
        return new EasySeoExtension();
    }
}
