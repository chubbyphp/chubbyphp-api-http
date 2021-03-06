<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ApiHttp\Unit\Serialization\ApiProblem\ClientError;

use Chubbyphp\ApiHttp\ApiProblem\ClientError\Locked;
use Chubbyphp\ApiHttp\Serialization\ApiProblem\ClientError\LockedMapping;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ApiHttp\Serialization\ApiProblem\ClientError\LockedMapping
 *
 * @internal
 */
final class LockedMappingTest extends TestCase
{
    public function testGetClass(): void
    {
        $mapping = new LockedMapping();

        self::assertSame(Locked::class, $mapping->getClass());
    }

    public function testGetNormalizationType(): void
    {
        $mapping = new LockedMapping();

        self::assertSame('apiProblem', $mapping->getNormalizationType());
    }

    public function testGetNormalizationFieldMappings(): void
    {
        $mapping = new LockedMapping();

        $fieldMappings = $mapping->getNormalizationFieldMappings('/');

        self::assertEquals([
            NormalizationFieldMappingBuilder::create('type')->getMapping(),
            NormalizationFieldMappingBuilder::create('title')->getMapping(),
            NormalizationFieldMappingBuilder::create('detail')->getMapping(),
            NormalizationFieldMappingBuilder::create('instance')->getMapping(),
        ], $fieldMappings);
    }

    public function testGetNormalizationEmbeddedFieldMappings(): void
    {
        $mapping = new LockedMapping();

        $embeddedFieldMappings = $mapping->getNormalizationEmbeddedFieldMappings('/');

        self::assertEquals([], $embeddedFieldMappings);
    }

    public function testGetNormalizationLinkMappings(): void
    {
        $mapping = new LockedMapping();

        $embeddedFieldMappings = $mapping->getNormalizationLinkMappings('/');

        self::assertEquals([], $embeddedFieldMappings);
    }
}
