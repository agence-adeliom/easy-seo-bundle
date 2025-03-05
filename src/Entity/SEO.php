<?php

namespace Adeliom\EasySeoBundle\Entity;

use Adeliom\EasyMediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SEO implements \Stringable
{
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, nullable: true)]
    public ?string $title = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(nullable: true)]
    public ?string $keywords;

    #[ORM\Column(nullable: true)]
    public ?string $cannonical;

    #[ORM\Column(type: 'easy_media_type', nullable: true)]
    public Media|string|null $cover;

    #[ORM\Column(nullable: true)]
    public ?string $key;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN, nullable: true)]
    public ?bool $sitemap = true;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::JSON)]
    public array $robots = [];

    public function __toString(): string
    {
        return $this->title;
    }
}
