<?php

namespace Adeliom\EasyCommonBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait EntityPublishableTrait
{
    #[Groups('main')]
    #[Assert\NotBlank]
    #[ORM\Column(name: 'publish_date', type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $publishDate;

    #[Groups('main')]
    #[Assert\Expression(expression: 'this.getUnpublishDate() == null or this.getUnpublishDate() > this.getPublishDate()', message: 'The unpublish date must be greater than the publish date')]
    #[ORM\Column(name: 'unpublish_date', type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $unpublishDate = null;

    /**
     * PublishableTrait constructor.
     */
    public function __construct()
    {
        $this->publishDate = new \DateTime();
    }

    public function getPublishDate(): \DateTimeInterface
    {
        return $this->publishDate;
    }

    /**
     * @return $this
     */
    public function setPublishDate(?\DateTimeInterface $publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getUnpublishDate(): ?\DateTimeInterface
    {
        return $this->unpublishDate;
    }

    /**
     * @return $this
     */
    public function setUnpublishDate(?\DateTimeInterface $unpublishDate)
    {
        $this->unpublishDate = $unpublishDate;

        return $this;
    }

    /**
     * Defines if the content is published.
     */
    public function isPublished(): bool
    {
        $now = new \DateTime();

        return
            (null === $this->getUnpublishDate() && $this->getPublishDate() <= $now) ||
            ($now <= $this->getUnpublishDate() && $this->getPublishDate() <= $now);
    }
}
