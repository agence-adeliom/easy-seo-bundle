<?php

namespace Adeliom\EasyCommonBundle\Traits;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait EntityThreeStateStatusTrait
{
    #[Groups('main')]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 100, enumType: ThreeStateStatusEnum::class)]
    private ThreeStateStatusEnum $state;

    /**
     * EntityThreeStateStatusTrait constructor.
     */
    public function __construct()
    {
        $this->state = ThreeStateStatusEnum::UNPUBLISHED;
    }

    public function getState(): ThreeStateStatusEnum
    {
        return $this->state;
    }

    public function setState(ThreeStateStatusEnum $state): void
    {
        $this->state = $state;
    }

    public function isState(ThreeStateStatusEnum $state): bool
    {
        return $this->state == $state;
    }
}
