<?php

namespace Adeliom\EasySeoBundle\Traits;

use Adeliom\EasySeoBundle\Entity\SEO;
use Doctrine\ORM\Mapping as ORM;

trait EntitySeoTrait {

    /**
     * The customer billing address.
     *
     * @var SEO
     * @ORM\Embedded(class="Adeliom\EasySeoBundle\Entity\SEO")
     */
    protected $seo;


    public function __construct()
    {
        $this->seo = new SEO();
    }

    /**
     * Set the address where the customer want its billing.
     *
     * @param SEO $seo
     */
    public function setSEO(SEO $seo)
    {
        $this->seo = $seo;
    }

    /**
     * @return SEO
     */
    public function getSEO() : SEO
    {
        return $this->seo;
    }

}
