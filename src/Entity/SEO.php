<?php

namespace Adeliom\EasySeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class SEO {

    /**
     * @var string
     * @ORM\Column
     */
    public $title;

    /**
     * @var string
     * @ORM\Column(type="text" ,nullable=true)
     */
    public $description;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    public $keywords;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    public $cannonical;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    public $cover;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    public $key;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    public $sitemap = true;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    public $robots = [];

    public function __toString()
    {
        return $this->title;
    }
}
