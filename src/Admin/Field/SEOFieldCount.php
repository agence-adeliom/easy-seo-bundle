<?php

declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Admin\Field;

use Adeliom\EasySeoBundle\Form\SeoCountType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class SEOFieldCount implements FieldInterface
{
    use FieldTrait;

    /**
     * @param string|false|null $label
     */
    public static function new(string $propertyName, $label = false): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->hideOnIndex()
            ->setTemplatePath('@EasySeo/seo-detail.html.twig')
            ->setFormType(SeoCountType::class)
            ->addCssFiles('bundles/easyseo/seo-form.css')
            ->addCssClass('field-seo')
            ->setDefaultColumns('') // this is set dynamically in the field configurator
        ;
    }
}
