<?php

namespace Chubbyphp\Tests\ApiHttp\Serialization\ApiProblem\ClientError;

use Chubbyphp\ApiHttp\ApiProblem\ClientError\RequestedRangeNotSatisfiable;
use Chubbyphp\ApiHttp\Serialization\ApiProblem\ClientError\RequestedRangeNotSatisfiableMapping;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ApiHttp\Serialization\ApiProblem\ClientError\RequestedRangeNotSatisfiableMapping
 */
final class RequestedRangeNotSatisfiableMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new RequestedRangeNotSatisfiableMapping();

        self::assertSame(RequestedRangeNotSatisfiable::class, $mapping->getClass());
    }

    public function testGetNormalizationType()
    {
        $mapping = new RequestedRangeNotSatisfiableMapping();

        self::assertSame('apiProblem', $mapping->getNormalizationType());
    }

    public function testGetNormalizationFieldMappings()
    {
        $mapping = new RequestedRangeNotSatisfiableMapping();

        $fieldMappings = $mapping->getNormalizationFieldMappings('/');

        self::assertEquals([
            NormalizationFieldMappingBuilder::create('type')->getMapping(),
            NormalizationFieldMappingBuilder::create('title')->getMapping(),
            NormalizationFieldMappingBuilder::create('detail')->getMapping(),
            NormalizationFieldMappingBuilder::create('instance')->getMapping(),
        ], $fieldMappings);
    }

    public function testGetNormalizationEmbeddedFieldMappings()
    {
        $mapping = new RequestedRangeNotSatisfiableMapping();

        $embeddedFieldMappings = $mapping->getNormalizationEmbeddedFieldMappings('/');

        self::assertEquals([], $embeddedFieldMappings);
    }

    public function testGetNormalizationLinkMappings()
    {
        $mapping = new RequestedRangeNotSatisfiableMapping();

        $embeddedFieldMappings = $mapping->getNormalizationLinkMappings('/');

        self::assertEquals([], $embeddedFieldMappings);
    }
}