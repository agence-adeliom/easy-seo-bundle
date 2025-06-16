<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Twig;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use Adeliom\EasySeoBundle\Twig\EasySeoExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class EasySeoExtensionTest extends TestCase
{
    public function testRenderFunctions(): void
    {
        $loader = new ArrayLoader([
            '@EasySeo/block-breadcrumb.html.twig' => 'bc={{ data|length }}',
            '@EasySeo/block-metas.html.twig' => 'title={{ data.title }}',
        ]);
        $twig = new Environment($loader);
        $dispatcher = new EventDispatcher();
        $breadcrumb = new BreadcrumbCollection();
        $breadcrumb->addSimpleItem('Home', '/');

        $extension = new EasySeoExtension(
            $twig,
            $dispatcher,
            $breadcrumb,
            ['separator' => '|', 'suffix' => 'Site'],
            []
        );

        $seo = new SEO();
        $seo->title = 'Welcome';
        $title = $extension->renderSeoTitle($seo);
        self::assertSame('Welcome | Site', $title);

        $markup = $extension->renderSeoMetas($seo);
        self::assertSame('title=Welcome', (string) $markup);

        $breadcrumbMarkup = $extension->renderBreadcrumb();
        self::assertSame('bc=1', (string) $breadcrumbMarkup);
    }
}
