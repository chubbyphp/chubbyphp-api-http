<?php

namespace Chubbyphp\Tests\ApiHttp\Manager;

use Chubbyphp\ApiHttp\Manager\ResponseManager;
use Chubbyphp\Deserialization\DeserializerInterface;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface;
use Chubbyphp\ApiHttp\ApiProblem\ApiProblemInterface;

/**
 * @covers \Chubbyphp\ApiHttp\Manager\ResponseManager
 */
final class ResponseManagerTest extends TestCase
{
    use MockByCallsTrait;

    public function testCreateWithDefaults()
    {
        $bodyString = '{"key": "value"}';

        $object = new \stdClass();

        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var StreamInterface|MockObject $body */
        $body = $this->getMockByCalls(StreamInterface::class, [
            Call::create('write')->with($bodyString),
        ]);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
            Call::create('getBody')->with()->willReturn($body),
        ]);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(200, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class, [
            Call::create('serialize')->with($object, 'application/json', null, '')->willReturn($bodyString),
        ]);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->create($object, 'application/json'));
    }

    public function testCreateWithoutDefaults()
    {
        $bodyString = '{"key": "value"}';

        $object = new \stdClass();

        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var StreamInterface|MockObject $body */
        $body = $this->getMockByCalls(StreamInterface::class, [
            Call::create('write')->with($bodyString),
        ]);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
            Call::create('getBody')->with()->willReturn($body),
        ]);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(201, '')->willReturn($response),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class, [
            Call::create('serialize')->with($object, 'application/json', $context, '')->willReturn($bodyString),
        ]);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->create($object, 'application/json', 201, $context));
    }

    public function testCreateEmptyWithDefaults()
    {
        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
        ]);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(204, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->createEmpty('application/json'));
    }

    public function testCreateEmptyWithoutDefaults()
    {
        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
        ]);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(200, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->createEmpty('application/json', 200));
    }

    public function testCreateRedirectWithDefaults()
    {
        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Location', 'https://google.com')->willReturnSelf(),
        ]);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(307, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->createRedirect('https://google.com'));
    }

    public function testCreateRedirectWithoutDefaults()
    {
        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Location', 'https://google.com')->willReturnSelf(),
        ]);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(301, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->createRedirect('https://google.com', 301));
    }

    public function testCreateFromApiProblem()
    {
        /** @var ApiProblemInterface|MockObject $apiProblem */
        $apiProblem = $this->getMockByCalls(ApiProblemInterface::class, [
            Call::create('getStatus')->with()->willReturn(404),
            Call::create('getHeaders')->with()->willReturn([]),
        ]);

        /** @var StreamInterface|MockObject $body */
        $body = $this->getMockByCalls(StreamInterface::class, [
            Call::create('write')->with('{"title":"Not found"}'),
        ]);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
            Call::create('getBody')->with()->willReturn($body),
        ]);

        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(404, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class, [
            Call::create('serialize')
                ->with($apiProblem, 'application/json', null, '')
                ->willReturn('{"title":"Not found"}'),
        ]);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->createFromApiProblem($apiProblem, 'application/json'));
    }

    public function testCreateFromApiProblemNotAcceptable()
    {
        /** @var ApiProblemInterface|MockObject $apiProblem */
        $apiProblem = $this->getMockByCalls(ApiProblemInterface::class, [
            Call::create('getStatus')->with()->willReturn(406),
            Call::create('getHeaders')->with()->willReturn(['X-Acceptable' => 'application/xml']),
        ]);

        /** @var Response|MockObject $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('X-Acceptable', 'application/xml')->willReturnSelf(),
        ]);

        /** @var DeserializerInterface|MockObject $deserializer */
        $deserializer = $this->getMockByCalls(DeserializerInterface::class);

        /** @var ResponseFactoryInterface|MockObject $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(406, '')->willReturn($response),
        ]);

        /** @var SerializerInterface|MockObject $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($deserializer, $responseFactory, $serializer);

        self::assertSame($response, $responseManager->createFromApiProblem($apiProblem, 'application/json'));
    }
}
