<?php

declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class TitleEvent extends Event
{
    public function __construct(
        private string $title,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
