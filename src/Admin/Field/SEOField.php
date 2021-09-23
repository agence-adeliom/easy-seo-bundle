<?php

namespace Adeliom\EasySeoBundle\Admin\Field;


use Adeliom\EasySeoBundle\Form\SeoType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class SEOField implements FieldInterface
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
            ->setFormType(SeoType::class)
            ->addCssFiles('bundles/easyseo/seo-form.css')
            ->addCssClass('field-seo')
            ->setDefaultColumns('') // this is set dynamically in the field configurator
        ;
    }
}
