<?php

namespace Chubbyphp\Tests\ApiHttp\Serialization\ApiProblem\ClientError;

use Chubbyphp\ApiHttp\ApiProblem\ClientError\NotAcceptable;
use Chubbyphp\ApiHttp\Serialization\ApiProblem\ClientError\NotAcceptableMapping;
use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ApiHttp\Serialization\ApiProblem\ClientError\NotAcceptableMapping
 */
final class NotAcceptableMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new NotAcceptableMapping();

        self::assertSame(NotAcceptable::class, $mapping->getClass());
    }

    public function testGetNormalizationType()
    {
        $mapping = new NotAcceptableMapping();

        self::assertSame('apiProblem', $mapping->getNormalizationType());
    }

    public function testGetNormalizationFieldMappings()
    {
        $mapping = new NotAcceptableMapping();

        $fieldMappings = $mapping->getNormalizationFieldMappings('/');

        self::assertEquals([
            NormalizationFieldMappingBuilder::create('type')
                ->setFieldNormalizer(new FieldNormalizer(new MethodAccessor('type')))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('title')
                ->setFieldNormalizer(new FieldNormalizer(new MethodAccessor('title')))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('detail')
                ->setFieldNormalizer(new FieldNormalizer(new MethodAccessor('detail')))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('instance')
                ->setFieldNormalizer(new FieldNormalizer(new MethodAccessor('instance')))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('acceptableMediaTypes')
                ->setFieldNormalizer(new FieldNormalizer(new MethodAccessor('acceptableMediaTypes')))
                ->getMapping(),
        ], $fieldMappings);
    }

    public function testGetNormalizationEmbeddedFieldMappings()
    {
        $mapping = new NotAcceptableMapping();

        $embeddedFieldMappings = $mapping->getNormalizationEmbeddedFieldMappings('/');

        self::assertEquals([], $embeddedFieldMappings);
    }

    public function testGetNormalizationLinkMappings()
    {
        $mapping = new NotAcceptableMapping();

        $embeddedFieldMappings = $mapping->getNormalizationLinkMappings('/');

        self::assertEquals([], $embeddedFieldMappings);
    }
}
