<?php

declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Event;

use Adeliom\EasySeoBundle\Entity\SEO;
use Symfony\Contracts\EventDispatcher\Event;

final class RenderMetaEvent extends Event
{
    public function __construct(
        private SEO $seo,
    ) {
    }

    public function getSeo(): SEO
    {
        return $this->seo;
    }

    public function setSeo(SEO $seo): void
    {
        $this->seo = $seo;
    }
}
