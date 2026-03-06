<?php

namespace Adeliom\EasySeoBundle\Tests\Form;

use Adeliom\EasyMediaBundle\Form\EasyMediaType;
use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Form\Fields\TextareaCounterType;
use Adeliom\EasySeoBundle\Form\Fields\TextCounterType;
use Adeliom\EasySeoBundle\Form\SeoCountType;
use Adeliom\EasySeoBundle\Form\SeoType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[CoversClass(\Adeliom\EasySeoBundle\Form\SeoType::class)]
#[CoversClass(\Adeliom\EasySeoBundle\Form\SeoCountType::class)]
final class SeoTypeTest extends TestCase
{
    public function testSeoTypeBuildsExpectedFieldsAndOptions(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::exactly(8))
            ->method('add')
            ->willReturnCallback(function (string $name, string $type, array $options = []) use ($builder): FormBuilderInterface {
                static $calls = 0;
                ++$calls;

                if (1 === $calls) {
                    self::assertSame('title', $name);
                    self::assertSame(TextType::class, $type);
                    self::assertSame('easy.seo.admin.field.title', $options['label']);
                }

                if (2 === $calls) {
                    self::assertSame('cover', $name);
                    self::assertSame(EasyMediaType::class, $type);
                    self::assertSame(['image/*'], $options['restrictions_uploadTypes']);
                }

                if (3 === $calls) {
                    self::assertSame('cannonical', $name);
                    self::assertSame(UrlType::class, $type);
                }

                if (4 === $calls) {
                    self::assertSame('description', $name);
                    self::assertSame(TextareaType::class, $type);
                }

                if (5 === $calls) {
                    self::assertSame('keywords', $name);
                    self::assertSame(TextType::class, $type);
                }

                if (6 === $calls) {
                    self::assertSame('key', $name);
                    self::assertSame(TextType::class, $type);
                }

                if (7 === $calls) {
                    self::assertSame('robots', $name);
                    self::assertSame(ChoiceType::class, $type);
                    self::assertSame('true', $options['multiple']);
                }

                if (8 === $calls) {
                    self::assertSame('sitemap', $name);
                    self::assertSame(CheckboxType::class, $type);
                }

                return $builder;
            });

        $type = new SeoType();
        $type->buildForm($builder, []);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);

        self::assertSame([
            'label' => false,
            'data_class' => SEO::class,
        ], $resolver->resolve());
        self::assertSame('easy_seo', $type->getBlockPrefix());
    }

    public function testSeoCountTypeBuildsExpectedCounterFields(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::exactly(8))
            ->method('add')
            ->willReturnCallback(function (string $name, string $type, array $options = []) use ($builder): FormBuilderInterface {
                static $calls = 0;
                ++$calls;

                if (1 === $calls) {
                    self::assertSame('title', $name);
                    self::assertSame(TextCounterType::class, $type);
                }

                if (2 === $calls) {
                    self::assertSame('cover', $name);
                    self::assertSame(EasyMediaType::class, $type);
                }

                if (3 === $calls) {
                    self::assertSame('cannonical', $name);
                    self::assertSame(UrlType::class, $type);
                }

                if (4 === $calls) {
                    self::assertSame('description', $name);
                    self::assertSame(TextareaCounterType::class, $type);
                }

                if (5 === $calls) {
                    self::assertSame('keywords', $name);
                    self::assertSame(TextType::class, $type);
                }

                if (6 === $calls) {
                    self::assertSame('key', $name);
                    self::assertSame(TextType::class, $type);
                }

                if (7 === $calls) {
                    self::assertSame('robots', $name);
                    self::assertSame(ChoiceType::class, $type);
                    self::assertSame('true', $options['multiple']);
                }

                if (8 === $calls) {
                    self::assertSame('sitemap', $name);
                    self::assertSame(CheckboxType::class, $type);
                }

                return $builder;
            });

        $type = new SeoCountType();
        $type->buildForm($builder, []);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);

        self::assertSame([
            'label' => false,
            'data_class' => SEO::class,
        ], $resolver->resolve());
        self::assertSame('easy_seo', $type->getBlockPrefix());
    }
}
