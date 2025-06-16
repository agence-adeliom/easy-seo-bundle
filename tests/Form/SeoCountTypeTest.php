<?php
declare(strict_types=1);

namespace Adeliom\EasySeoBundle\Tests\Form;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Form\SeoCountType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SeoCountTypeTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $type = new SeoCountType();
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertSame(SEO::class, $options['data_class']);
        self::assertFalse($options['label']);
        self::assertSame('easy_seo', $type->getBlockPrefix());
    }
}
