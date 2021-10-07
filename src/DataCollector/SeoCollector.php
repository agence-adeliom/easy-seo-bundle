<?php

namespace Adeliom\EasySeoBundle\DataCollector;


use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;
use Adeliom\EasySeoBundle\Twig\EasySeoExtension;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;


final class SeoCollector extends AbstractDataCollector
{
    private const MAX_PANEL_WIDTH = 50;
    private const CLASS_ERROR = 'red';
    private const CLASS_WARNING = 'yellow';
    private const CLASS_OK = 'green';

    private const METRIC_CLASS_ERROR = 'status-error';
    private const METRIC_CLASS_WARNING = 'status-warning';
    private const METRIC_CLASS_OK = 'status-sucess';

    /** @var BreadcrumbCollection */
    protected $breadcrumb;
    protected $config;

    public function __construct(BreadcrumbCollection $breadcrumb, $config)
    {
        $this->breadcrumb = $breadcrumb;
        $this->config = $config;
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $crawler = new Crawler((string) $response->getContent());

        $this->data['breadcrumb'] = $this->breadcrumb->getItems();
        $this->data['config'] = $this->config;

        // Title ———————————————————————————————————————————————————————————————
        $titleTag = $crawler->filter('title');
        if ($titleTag->count() > 0) {
            $titleStr = u($titleTag->text());
            $titleSize = $titleStr->length();
            $titleInfo = [
                'value' => $titleStr->wordwrap(self::MAX_PANEL_WIDTH)->toString(),
                'size' => (string) $titleSize,
                'status' => $this->getTitleClass($titleSize),
                'metric' => $this->getTitleStatusClass($titleSize),
            ];
            $this->data['title'] = $titleInfo;
        }

        // Description —————————————————————————————————————————————————————————
        $meta = $crawler->filterXPath('//meta[@name="description"]');
        if ($meta->count() > 0) {
            $descriptionStr = u((string) $meta->attr('content'));
            $descriptionLength = $descriptionStr->length();
            $descriptionInfo = [
                'value' => $descriptionStr->wordwrap(self::MAX_PANEL_WIDTH)->toString(),
                'size' => (string) $descriptionLength,
                'status' => $this->getDescriptionClass($descriptionLength),
                'metric' => $this->getDescriptionStatusClass($descriptionLength),
            ];
            $this->data['description'] = $descriptionInfo;
        }

        $meta = $crawler->filterXPath('//meta[@name="keywords"]');
        if ($meta->count() > 0) {
            $this->data['keywords'] = [
                'value' => u((string) $meta->attr('content'))
            ];
        }

        $meta = $crawler->filterXPath('//meta[@name="robots"]');
        if ($meta->count() > 0) {
            $this->data['robots'] = [
                'value' => u((string) $meta->attr('content'))
            ];
        }

        $meta = $crawler->filterXPath('//meta[@name="page-key"]');
        if ($meta->count() > 0) {
            $this->data['pageKey'] = [
                'value' => u((string) $meta->attr('content'))
            ];
        }

        $meta = $crawler->filterXPath('//link[@rel="canonical"]');
        if ($meta->count() > 0) {
            $this->data['canonical'] = [
                'value' => u((string) $meta->attr('href'))
            ];
        }

        $meta = $crawler->filterXPath('//meta[@property="og:image"]');
        if ($meta->count() > 0) {
            $this->data['cover'] = [
                'value' => u((string) $meta->attr('content'))
            ];
        }

    }

    private function getTitleClass(int $size): string
    {
        if ($size === 0) {
            return self::CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_TITLE_LENGTH && $size <= EasySeoExtension::MAX_TITLE_LENGTH ? self::CLASS_OK : self::CLASS_WARNING;
    }

    private function getTitleStatusClass(int $size): string
    {
        if ($size === 0) {
            return self::METRIC_CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_TITLE_LENGTH && $size <= EasySeoExtension::MAX_TITLE_LENGTH ? self::METRIC_CLASS_OK : self::METRIC_CLASS_WARNING;
    }

    private function getDescriptionClass(int $size): string
    {
        if ($size === 0) {
            return self::CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_DESCRITION_LENGTH && $size <= EasySeoExtension::MAX_DESCRITION_LENGTH ? self::CLASS_OK : self::CLASS_WARNING;
    }

    private function getDescriptionStatusClass(int $size): string
    {
        if ($size === 0) {
            return self::METRIC_CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_DESCRITION_LENGTH && $size <= EasySeoExtension::MAX_DESCRITION_LENGTH ? self::METRIC_CLASS_OK : self::METRIC_CLASS_WARNING;
    }

    /**
     * @return array<string,string>
     */
    public function getTitle(): array
    {
        return $this->data['title'] ?? [];
    }

    /**
     * @return array<string,string>
     */
    public function getDescription(): array
    {
        return $this->data['description'] ?? [];
    }


    public function __get($name)
    {
        return $this->data[$name] ?? [];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return self::class;
    }
}
