<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ApiHttp\Unit\Manager;

use Chubbyphp\ApiHttp\Manager\ResponseManager;
use Chubbyphp\HttpException\HttpException;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Chubbyphp\ApiHttp\Manager\ResponseManager
 *
 * @internal
 */
final class ResponseManagerTest extends TestCase
{
    use MockByCallsTrait;

    public function testCreateWithDefaults(): void
    {
        $bodyString = '{"key": "value"}';

        $object = new \stdClass();

        /** @var MockObject|StreamInterface $body */
        $body = $this->getMockByCalls(StreamInterface::class, [
            Call::create('write')->with($bodyString)->willReturn(\strlen($bodyString)),
        ]);

        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
            Call::create('getBody')->with()->willReturn($body),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(200, '')->willReturn($response),
        ]);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class, [
            Call::create('serialize')->with($object, 'application/json', null, '')->willReturn($bodyString),
        ]);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->create($object, 'application/json'));
    }

    public function testCreateWithoutDefaults(): void
    {
        $bodyString = '{"key": "value"}';

        $object = new \stdClass();

        /** @var MockObject|StreamInterface $body */
        $body = $this->getMockByCalls(StreamInterface::class, [
            Call::create('write')->with($bodyString)->willReturn(\strlen($bodyString)),
        ]);

        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
            Call::create('getBody')->with()->willReturn($body),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(201, '')->willReturn($response),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class, [
            Call::create('serialize')->with($object, 'application/json', $context, '')->willReturn($bodyString),
        ]);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->create($object, 'application/json', 201, $context));
    }

    public function testCreateEmptyWithDefaults(): void
    {
        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(204, '')->willReturn($response),
        ]);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->createEmpty('application/json'));
    }

    public function testCreateEmptyWithoutDefaults(): void
    {
        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/json')->willReturnSelf(),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(200, '')->willReturn($response),
        ]);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->createEmpty('application/json', 200));
    }

    public function testCreateRedirectWithDefaults(): void
    {
        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Location', 'https://google.com')->willReturnSelf(),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(307, '')->willReturn($response),
        ]);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->createRedirect('https://google.com'));
    }

    public function testCreateRedirectWithoutDefaults(): void
    {
        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Location', 'https://google.com')->willReturnSelf(),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(301, '')->willReturn($response),
        ]);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->createRedirect('https://google.com', 301));
    }

    public function testCreateFromHttpException(): void
    {
        $bodyString = '{"title":"Method Not Allowed"}';

        $httpException = HttpException::createMethodNotAllowed(['allowedMethods' => ['PATCH', 'PUT']]);

        /** @var MockObject|StreamInterface $body */
        $body = $this->getMockByCalls(StreamInterface::class, [
            Call::create('write')->with($bodyString)->willReturn(\strlen($bodyString)),
        ]);

        /** @var MockObject|Response $response */
        $response = $this->getMockByCalls(Response::class, [
            Call::create('withHeader')->with('Content-Type', 'application/problem+json')->willReturnSelf(),
            Call::create('getBody')->with()->willReturn($body),
        ]);

        /** @var MockObject|ResponseFactoryInterface $responseFactory */
        $responseFactory = $this->getMockByCalls(ResponseFactoryInterface::class, [
            Call::create('createResponse')->with(405, '')->willReturn($response),
        ]);

        /** @var MockObject|SerializerInterface $serializer */
        $serializer = $this->getMockByCalls(SerializerInterface::class, [
            Call::create('encode')
                ->with([
                    'type' => 'https://datatracker.ietf.org/doc/html/rfc2616#section-10.4.6',
                    'status' => 405,
                    'title' => 'Method Not Allowed',
                    'detail' => null,
                    'instance' => null,
                    'allowedMethods' => ['PATCH', 'PUT'],
                    '_type' => 'apiProblem',
                ], 'application/json')
                ->willReturn('{"title":"Method Not Allowed"}'),
        ]);

        $responseManager = new ResponseManager($responseFactory, $serializer);

        self::assertSame($response, $responseManager->createFromHttpException($httpException, 'application/json'));
    }
}
