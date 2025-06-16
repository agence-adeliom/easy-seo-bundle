<?php
declare(strict_types=1);

namespace Adeliom\EasySeo\Tests\Fixtures;

use Adeliom\EasySeoBundle\Entity\SEO;

final class SeoFactory
{
    public static function createSeo(
        string $title = 'Title',
        string $description = 'Description',
        string $keywords = 'keyword',
        string $cannonical = '/url',
        ?string $key = 'key'
    ): SEO {
        $seo = new SEO();
        $seo->title = $title;
        $seo->description = $description;
        $seo->keywords = $keywords;
        $seo->cannonical = $cannonical;
        $seo->key = $key;
        return $seo;
    }
}
