<?php

namespace Adeliom\EasySeoBundle\Twig;

use Adeliom\EasySeoBundle\Entity\SEO;
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
            new TwigFunction('seo_metas', \Closure::fromCallable(fn (\Adeliom\EasySeoBundle\Entity\SEO $seo) => $this->renderSeoMetas($seo))),
            new TwigFunction('seo_title', \Closure::fromCallable(fn ($seo) => $this->renderSeoTitle($seo))),
            new TwigFunction('seo_breadcrumb', \Closure::fromCallable(fn () => $this->renderBreadcrumb())),
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
        $event = new GenericEvent(null, ['items' => $this->breadcrumb->getItems()]);
        /**
         * @var GenericEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, 'easyseo.breadcrumb');

        return new Markup($this->twig->render('@EasySeo/block-breadcrumb.html.twig', ['data' => $result->getArgument('items')]), 'UTF-8');
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

        $event = new GenericEvent(null, ['title' => $title]);
        /**
         * @var GenericEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, 'easyseo.title');

        return $result->getArgument('title') ?: $title;
    }

    public function renderSeoMetas(SEO $seo): Markup
    {
        $event = new GenericEvent(null, ['datas' => $seo]);
        /**
         * @var GenericEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, 'easyseo.render_meta');

        return new Markup($this->twig->render('@EasySeo/block-metas.html.twig', ['data' => ($result->getArgument('datas') ?: $seo)]), 'UTF-8');
    }
}
