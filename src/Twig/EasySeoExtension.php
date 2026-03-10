<?php

namespace Adeliom\EasySeoBundle\Twig;

use Adeliom\EasyCommonBundle\Event\LegacyEventDispatcher;
use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Event\BreadcrumbEvent;
use Adeliom\EasySeoBundle\Event\RenderMetaEvent;
use Adeliom\EasySeoBundle\Event\TitleEvent;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\Markup;
use Twig\TwigFunction;

class EasySeoExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var int
     */
    public const MIN_TITLE_LENGTH = 30;

    /**
     * @var int
     */
    public const MAX_TITLE_LENGTH = 65;

    /**
     * @var int
     */
    public const MIN_DESCRITION_LENGTH = 120;

    /**
     * @var int
     */
    public const MAX_DESCRITION_LENGTH = 155;

    public function __construct(protected Environment $twig, protected EventDispatcherInterface $eventDispatcher, protected BreadcrumbCollection $breadcrumb, protected $titleConfig, protected $breadcrumbConfig)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('seo_metas', $this->renderSeoMetas(...)),
            new TwigFunction('seo_title', $this->renderSeoTitle(...)),
            new TwigFunction('seo_breadcrumb', $this->renderBreadcrumb(...)),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'easy_seo_title' => $this->titleConfig,
            'easy_seo_breadcrumb' => $this->breadcrumbConfig,
        ];
    }

    public function renderBreadcrumb(): Markup
    {
        $result = LegacyEventDispatcher::dispatchGenericEvent(
            new BreadcrumbEvent($this->breadcrumb->getItems()),
            $this->eventDispatcher,
            'agence-adeliom/easy-seo-bundle',
            'easyseo.breadcrumb',
            static fn (BreadcrumbEvent $event): GenericEvent => new GenericEvent(null, ['items' => $event->getItems()]),
            static function (BreadcrumbEvent $event, GenericEvent $legacyEvent): void {
                $event->setItems($legacyEvent->getArgument('items'));
            }
        );

        return new Markup($this->twig->render('@EasySeo/block-breadcrumb.html.twig', ['data' => $result->getItems()]), 'UTF-8');
    }

    public function renderSeoTitle($seo): string
    {
        $title = '';
        if (is_string($seo)) {
            $title = $seo;
        }

        if ($seo instanceof SEO) {
            $title = $seo->title;
        }

        if (!empty($this->titleConfig['suffix'])) {
            $title = sprintf('%s %s %s', $title, $this->titleConfig['separator'], $this->titleConfig['suffix']);
        }

        $result = LegacyEventDispatcher::dispatchGenericEvent(
            new TitleEvent($title),
            $this->eventDispatcher,
            'agence-adeliom/easy-seo-bundle',
            'easyseo.title',
            static fn (TitleEvent $event): GenericEvent => new GenericEvent(null, ['title' => $event->getTitle()]),
            static function (TitleEvent $event, GenericEvent $legacyEvent): void {
                $event->setTitle($legacyEvent->getArgument('title'));
            }
        );

        return $result->getTitle() ?: $title;
    }

    public function renderSeoMetas(SEO $seo): Markup
    {
        $result = LegacyEventDispatcher::dispatchGenericEvent(
            new RenderMetaEvent($seo),
            $this->eventDispatcher,
            'agence-adeliom/easy-seo-bundle',
            'easyseo.render_meta',
            static fn (RenderMetaEvent $event): GenericEvent => new GenericEvent(null, ['datas' => $event->getSeo()]),
            static function (RenderMetaEvent $event, GenericEvent $legacyEvent): void {
                $event->setSeo($legacyEvent->getArgument('datas'));
            }
        );

        return new Markup($this->twig->render('@EasySeo/block-metas.html.twig', ['data' => $result->getSeo()]), 'UTF-8');
    }
}
