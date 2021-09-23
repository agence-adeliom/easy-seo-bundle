<?php

namespace Adeliom\EasySeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Adeliom\EasySeoBundle\DependencyInjection\EasySeoExtension;

class EasySeoBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EasySeoExtension();
    }
}
