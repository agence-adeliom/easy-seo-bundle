<?php

namespace Adeliom\EasyCommonBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityStatusTrait
{
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
    private bool $status = false;

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status = false): void
    {
        $this->status = $status;
    }
}
