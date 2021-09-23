<?php


namespace Adeliom\EasySeoBundle\Twig;

use Adeliom\EasyBlogBundle\Event\EasyBlogCategoryEvent;
use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Services\BreadCrumbCollection;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\Markup;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EasySeoExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var Environment
     */
    protected $twig;
    protected $eventDispatcher;
    protected $breadcrumb;

    protected $titleConfig;
    protected $breadcrumbConfig;

    public function __construct(Environment $twig, EventDispatcherInterface $eventDispatcher, BreadCrumbCollection $breadcrumb, $titleConfig, $breadcrumbConfig)
    {
        $this->twig = $twig;
        $this->eventDispatcher = $eventDispatcher;
        $this->breadcrumb = $breadcrumb;
        $this->titleConfig = $titleConfig;
        $this->breadcrumbConfig = $breadcrumbConfig;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('seo_metas', [$this, 'renderSeoMetas']),
            new TwigFunction('seo_title', [$this, 'renderSeoTitle']),
            new TwigFunction('seo_breadcrumb', [$this, 'renderBreadcrumb']),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'easy_seo_title' => $this->titleConfig,
            'easy_seo_breadcrumb' => $this->breadcrumbConfig,
        ];
    }
    public function renderBreadcrumb()
    {
        $event = new GenericEvent(null, ['items' => $this->breadcrumb->getItems() ]);
        /**
         * @var GenericEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, "easyseo.breadcrumb");
        return new Markup($this->twig->render('@EasySeo/block-breadcrumb.html.twig', ["data" => $result->getArgument('items')]), 'UTF-8');
    }

    public function renderSeoTitle($seo){
        $title = "";
        if(is_string($seo)){
            $title = $seo;
        }
        if($seo instanceof SEO){
            $title = $seo->title;
        }

        if(!empty($this->titleConfig["suffix"])){
            $title = sprintf("%s %s %s", $title, $this->titleConfig["separator"], $this->titleConfig["suffix"]);
        }

        $event = new GenericEvent(null, ['title' => $title ]);
        /**
         * @var GenericEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, "easyseo.title");
        return $result->getArgument('title') ?: $title;
    }

    public function renderSeoMetas(SEO $seo){
        $event = new GenericEvent(null, ['datas' => $seo ]);
        /**
         * @var GenericEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, "easyseo.render_meta");
        return new Markup($this->twig->render('@EasySeo/block-metas.html.twig', ["data" => ($result->getArgument('datas') ?: $seo)]), 'UTF-8');
    }
}
