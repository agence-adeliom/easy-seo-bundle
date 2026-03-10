<?php

namespace Adeliom\EasySeoBundle\Tests\Twig;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Event\BreadcrumbEvent;
use Adeliom\EasySeoBundle\Event\RenderMetaEvent;
use Adeliom\EasySeoBundle\Event\TitleEvent;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use Adeliom\EasySeoBundle\Twig\EasySeoExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;
use Twig\Environment;
use Twig\Markup;
use Twig\TwigFunction;

#[CoversClass(\Adeliom\EasySeoBundle\Twig\EasySeoExtension::class)]
final class EasySeoExtensionTest extends TestCase
{
    public function testExtensionRegistersFunctionsAndGlobals(): void
    {
        $extension = new EasySeoExtension(
            $this->createMock(Environment::class),
            new EventDispatcher(),
            new BreadcrumbCollection(),
            ['separator' => '|', 'suffix' => 'Adeliom'],
            ['class' => 'breadcrumb']
        );

        self::assertContainsOnlyInstancesOf(TwigFunction::class, $extension->getFunctions());
        self::assertSame(['seo_metas', 'seo_title', 'seo_breadcrumb'], array_map(
            static fn (TwigFunction $function): string => $function->getName(),
            $extension->getFunctions()
        ));
        self::assertSame([
            'easy_seo_title' => ['separator' => '|', 'suffix' => 'Adeliom'],
            'easy_seo_breadcrumb' => ['class' => 'breadcrumb'],
        ], $extension->getGlobals());
    }

    public function testRenderSeoTitleAppliesSuffixAndDispatcherOverride(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(TitleEvent::class, static function (TitleEvent $event): void {
            $event->setTitle('Overridden title');
        });

        $extension = new EasySeoExtension(
            $this->createMock(Environment::class),
            $dispatcher,
            new BreadcrumbCollection(),
            ['separator' => '|', 'suffix' => 'Adeliom'],
            []
        );

        self::assertSame('Overridden title', $extension->renderSeoTitle('Homepage'));
    }

    public function testRenderSeoTitleAcceptsSeoObjectWithoutSuffix(): void
    {
        $extension = new EasySeoExtension(
            $this->createMock(Environment::class),
            new EventDispatcher(),
            new BreadcrumbCollection(),
            ['separator' => '|', 'suffix' => ''],
            []
        );

        $seo = new SEO();
        $seo->title = 'SEO object title';

        self::assertSame('SEO object title', $extension->renderSeoTitle($seo));
    }

    public function testRenderSeoMetasAndBreadcrumbReturnMarkup(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig
            ->expects(self::exactly(2))
            ->method('render')
            ->willReturnCallback(static function (string $template, array $context): string {
                if ('@EasySeo/block-metas.html.twig' === $template) {
                    TestCase::assertArrayHasKey('data', $context);
                    TestCase::assertInstanceOf(SEO::class, $context['data']);

                    return '<meta />';
                }

                TestCase::assertSame('@EasySeo/block-breadcrumb.html.twig', $template);
                TestCase::assertSame([
                    ['linkName' => 'Homepage', 'target' => null, 'object' => null],
                ], $context['data']);

                return '<nav />';
            });

        $breadcrumbs = new BreadcrumbCollection();
        $breadcrumbs->addSimpleItem('Homepage');

        $extension = new EasySeoExtension(
            $twig,
            new EventDispatcher(),
            $breadcrumbs,
            ['separator' => '|', 'suffix' => ''],
            ['class' => 'breadcrumb']
        );

        $seo = new SEO();
        $seo->title = 'Homepage';

        $metas = $extension->renderSeoMetas($seo);
        $breadcrumb = $extension->renderBreadcrumb();

        self::assertInstanceOf(Markup::class, $metas);
        self::assertSame('<meta />', (string) $metas);
        self::assertInstanceOf(Markup::class, $breadcrumb);
        self::assertSame('<nav />', (string) $breadcrumb);
    }

    public function testRenderSeoTitleKeepsLegacyGenericListenersWorking(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('easyseo.title', static function (GenericEvent $event): void {
            $event->setArgument('title', 'Legacy title');
        });

        $extension = new EasySeoExtension(
            $this->createMock(Environment::class),
            $dispatcher,
            new BreadcrumbCollection(),
            ['separator' => '|', 'suffix' => ''],
            []
        );

        self::assertSame('Legacy title', $extension->renderSeoTitle('Homepage'));
    }
}
