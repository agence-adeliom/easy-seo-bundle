<?php

declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class BreadcrumbEvent extends Event
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        private array $items,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
