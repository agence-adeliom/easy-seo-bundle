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
    /**
     * @var int
     */
    private const MAX_PANEL_WIDTH = 50;

    /**
     * @var string
     */
    private const CLASS_ERROR = 'red';

    /**
     * @var string
     */
    private const CLASS_WARNING = 'yellow';

    /**
     * @var string
     */
    private const CLASS_OK = 'green';

    /**
     * @var string
     */
    private const METRIC_CLASS_ERROR = 'status-error';

    /**
     * @var string
     */
    private const METRIC_CLASS_WARNING = 'status-warning';

    /**
     * @var string
     */
    private const METRIC_CLASS_OK = 'status-sucess';

    public function __construct(protected BreadcrumbCollection $breadcrumb, protected ?array $config, protected bool $enabled = false, protected array $ignore = [])
    {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data['config'] = $this->config;
        $this->data['enabled'] = $this->enabled;
        $this->data['ignored'] = false;

        if (!$this->enabled) {
            return;
        }

        $uri = $request->getPathInfo();
        $match = array_filter($this->ignore, static function ($ignore) use ($uri) {
            return preg_match('{'.$ignore.'}', rawurldecode($uri));
        });

        if (!empty($match) || in_array($request->attributes->get('_route'), $this->ignore)) {
            $this->data['ignored'] = true;
            return;
        }

        $crawler = new Crawler((string) $response->getContent());

        $this->data['breadcrumb'] = $this->breadcrumb->getItems();

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
                'value' => u((string) $meta->attr('content')),
            ];
        }

        $meta = $crawler->filterXPath('//meta[@name="robots"]');
        if ($meta->count() > 0) {
            $this->data['robots'] = [
                'value' => u((string) $meta->attr('content')),
            ];
        }

        $meta = $crawler->filterXPath('//meta[@name="page-key"]');
        if ($meta->count() > 0) {
            $this->data['pageKey'] = [
                'value' => u((string) $meta->attr('content')),
            ];
        }

        $meta = $crawler->filterXPath('//link[@rel="canonical"]');
        if ($meta->count() > 0) {
            $this->data['canonical'] = [
                'value' => u((string) $meta->attr('href')),
            ];
        }

        $meta = $crawler->filterXPath('//meta[@property="og:image"]');
        if ($meta->count() > 0) {
            $this->data['cover'] = [
                'value' => u((string) $meta->attr('content')),
            ];
        }
    }

    private function getTitleClass(int $size): string
    {
        if (0 === $size) {
            return self::CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_TITLE_LENGTH && $size <= EasySeoExtension::MAX_TITLE_LENGTH ? self::CLASS_OK : self::CLASS_WARNING;
    }

    private function getTitleStatusClass(int $size): string
    {
        if (0 === $size) {
            return self::METRIC_CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_TITLE_LENGTH && $size <= EasySeoExtension::MAX_TITLE_LENGTH ? self::METRIC_CLASS_OK : self::METRIC_CLASS_WARNING;
    }

    private function getDescriptionClass(int $size): string
    {
        if (0 === $size) {
            return self::CLASS_ERROR;
        }

        return $size >= EasySeoExtension::MIN_DESCRITION_LENGTH && $size <= EasySeoExtension::MAX_DESCRITION_LENGTH ? self::CLASS_OK : self::CLASS_WARNING;
    }

    private function getDescriptionStatusClass(int $size): string
    {
        if (0 === $size) {
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
