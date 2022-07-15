<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetTriangle extends ApiTestCase
{
    private const REQUEST_URI = '/triangle/';

    private const VALIDATION_RULES = [
        'a' => ['notEmpty', 'float'],
        'b' => ['notEmpty', 'float'],
        'c' => ['notEmpty', 'float'],
        'surface' => ['notEmpty', 'float'],
        'circumference' => ['notEmpty', 'float'],
    ];

    public function testGet(): void
    {
        $response = $this->request(
            Request::METHOD_GET,
            self::REQUEST_URI . "3/4/5"
        );

        $this->dumpResponseIfCodeNotExpected($response, Response::HTTP_OK);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        self::assertIsArray($responseData);
        self::assertNotEmpty($responseData);
        $this->validateRecordsBasedOnRules([$responseData], self::VALIDATION_RULES);

        self::assertEquals(6, $responseData['surface']);
        self::assertEquals(12, $responseData['circumference']);
    }

}