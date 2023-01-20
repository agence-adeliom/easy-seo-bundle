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
    public $title;

    /**
     * @var string
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    public ?string $description = null;

    /**
     * @var string
     */
    #[ORM\Column(nullable: true)]
    public $keywords;

    /**
     * @var string
     */
    #[ORM\Column(nullable: true)]
    public $cannonical;

    /**
     * @var string
     */
    #[ORM\Column(type: 'easy_media_type', nullable: true)]
    public $cover;

    /**
     * @var string
     */
    #[ORM\Column(nullable: true)]
    public $key;

    /**
     * @var bool
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::BOOLEAN)]
    public ?bool $sitemap = true;

    /**
     * @var array
     */
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::JSON)]
    public $robots = [];

    public function __toString(): string
    {
        return $this->title;
    }
}
