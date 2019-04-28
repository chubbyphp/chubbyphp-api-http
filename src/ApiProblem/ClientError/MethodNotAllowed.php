<?php

declare(strict_types=1);

namespace Chubbyphp\ApiHttp\ApiProblem\ClientError;

use Chubbyphp\ApiHttp\ApiProblem\AbstractApiProblem;

final class MethodNotAllowed extends AbstractApiProblem
{
    /**
     * @var string[]
     */
    private $allowedMethods = [];

    /**
     * @param string[]    $allowedMethods
     * @param string|null $detail
     * @param string|null $instance
     */
    public function __construct(array $allowedMethods, string $detail = null, string $instance = null)
    {
        parent::__construct(
            'https://tools.ietf.org/html/rfc2616#section-10.4.6',
            405,
            'Method Not Allowed',
            $detail,
            $instance
        );

        $this->allowedMethods = $allowedMethods;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        if ([] === $this->allowedMethods) {
            return [];
        }

        return ['Allow' => implode(',', $this->allowedMethods)];
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}