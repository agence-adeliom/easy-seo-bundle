<?php

namespace Adeliom\EasySeoBundle\Form;

use Adeliom\EasyMediaBundle\Form\EasyMediaType;
use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Form\Fields\TextareaCounterType;
use Adeliom\EasySeoBundle\Form\Fields\TextCounterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeoCountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextCounterType::class, [
                'label' => 'easy.seo.admin.field.title',
            ])
            ->add('cover', EasyMediaType::class, [
                'label' => 'easy.seo.admin.field.cover',
                'restrictions_uploadTypes' => ['image/*'],
            ])
            ->add('cannonical', UrlType::class, [
                'label' => 'easy.seo.admin.field.cannonical',
            ])
            ->add('description', TextareaCounterType::class, [
                'label' => 'easy.seo.admin.field.description',
            ])
            ->add('keywords', TextType::class, [
                'label' => 'easy.seo.admin.field.keywords',
            ])
            ->add('key', TextType::class, [
                'label' => 'easy.seo.admin.field.key',
            ])
            ->add('robots', ChoiceType::class, [
                'label' => 'easy.seo.admin.field.robots',
                'multiple' => 'true',
                'attr' => [
                    'data-ea-widget' => 'ea-autocomplete',
                ],
                'choices' => [
                    'noindex' => 'noindex',
                    'nofollow' => 'nofollow',
                    'noarchive' => 'noarchive',
                    'nosnippet' => 'nosnippet',
                    'notranslate' => 'notranslate',
                    'noimageindex' => 'noimageindex',
                ],
            ])
            ->add('sitemap', CheckboxType::class, [
                'label' => 'easy.seo.admin.field.sitemap',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'data_class' => SEO::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'easy_seo';
    }
}
