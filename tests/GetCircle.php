<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCircle extends ApiTestCase
{
    private const REQUEST_URI = '/circle/';

    private const VALIDATION_RULES = [
        'type' => ['notEmpty', 'string'],
        'radius' => ['notEmpty', 'float'],
        'surface' => ['notEmpty', 'float'],
        'circumference' => ['notEmpty', 'float'],
    ];

    public function testGet(): void
    {
        $response = $this->request(
            Request::METHOD_GET,
            self::REQUEST_URI . "2"
        );

        $this->dumpResponseIfCodeNotExpected($response, Response::HTTP_OK);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        self::assertIsArray($responseData);
        self::assertNotEmpty($responseData);
        $this->validateRecordsBasedOnRules([$responseData], self::VALIDATION_RULES);

        self::assertEquals(12.57, $responseData['surface']);
        self::assertEquals(12.57, $responseData['circumference']);
        self::assertEquals('circle', $responseData['type']);
    }

}