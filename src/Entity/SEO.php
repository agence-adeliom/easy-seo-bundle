<?php

namespace Adeliom\EasySeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SEO implements \Stringable
{
    /**
     * @var string
     */
    #[ORM\Column]
    public string $title;

    /**
     * @var string|null
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    public ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(nullable: true)]
    public ?string $keywords;

    /**
     * @var string|null
     */
    #[ORM\Column(nullable: true)]
    public ?string $cannonical;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'easy_media_type', nullable: true)]
    public ?string $cover;

    /**
     * @var string|null
     */
    #[ORM\Column(nullable: true)]
    public ?string $key;

    /**
     * @var bool
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
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
