<?php

declare(strict_types=1);

namespace App\Tests;

use App\Kernel;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{
    protected bool $dumpFailedResponses = true;
    protected AbstractBrowser $client;

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    protected function request(string $method, string $uri, $content = null, array $headers = []): Response
    {
        $server = ['CONTENT_TYPE' => 'application/ld+json', 'HTTP_ACCEPT' => 'application/ld+json, application/json'];
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'content-type') {
                $server['CONTENT_TYPE'] = $value;

                continue;
            }

            $server['HTTP_' . strtoupper(str_replace('-', '_', $key))] = $value;
        }

        if (is_array($content) && false !== preg_match('#^application/(?:.+\+)?json$#', $server['CONTENT_TYPE'])) {
            $content = json_encode($content);
        }

        $this->client->request($method, $uri, [], [], $server, $content);

        return $this->client->getResponse();
    }

    protected function dumpResponseIfCodeNotExpected(
        Response $response,
        int $expectedCode,
        ?string $message = null
    ): void {
        $gotCode = $response->getStatusCode();
        if ($this->dumpFailedResponses && ($gotCode !== $expectedCode)) {
            $stack = debug_backtrace(0, 2);
            $callingPointSourceLine = $stack[0]['line'];
            $callingPointSourceClass = $stack[1]['class'];
            $callingPointSourceMethod = $stack[1]['function'];
            try {
                $responseDecoded = json_decode($response->getContent());
                unset($responseDecoded->trace);
                $content = json_encode($responseDecoded, JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                $content = $response->getContent();
            }
            $this->fail(
                ($message ? "$message:\n" : '') .
                "Got response code $gotCode instead of $expectedCode at line $callingPointSourceLine" .
                " of $callingPointSourceClass::$callingPointSourceMethod\n" .
                "\nResponse:\n" . $content
            );
        }
    }

    /**
     * Validate records based on rules
     *
     * Example of rules:
     * [
     *      'id' => ['notNull'],  // "id" key must be present and not null
     *      'description' => [],  // "description" key must only be present
     * ]
     * @param array $responseRows
     * @param array $rules
     */
    protected function validateRecordsBasedOnRules(array $responseRows, array $rules): void
    {
        foreach ($responseRows as $row) {
            foreach ($rules as $ruleKey => $ruleOptions) { // id, description ...
                $ruleKeyPieces = explode('.', $ruleKey);
                switch (count($ruleKeyPieces)) {
                    case 1:
                        $actualValue = $row[$ruleKey];
                        break;
                    case 2:
                        $this->assertArrayHasKey($ruleKeyPieces[0], $row);
                        $this->assertArrayHasKey($ruleKeyPieces[1], $row[$ruleKeyPieces[0]]);
                        $actualValue = $row[$ruleKeyPieces[0]][$ruleKeyPieces[1]];
                        break;
                    default:
                        throw new InvalidArgumentException("rule key {$ruleKey}not supported");
                }

                if ($actualValue === null && in_array('nullable', $ruleOptions, true)) {
                    continue;
                }
                if (in_array('integer', $ruleOptions, true)) {
                    $this->assertMatchesRegularExpression('/^-?\d+$/', $actualValue);
                }
                if (in_array('boolean', $ruleOptions, true)) {
                    $this->assertIsBool($actualValue);
                }
                if ($oneOf = $ruleOptions['oneOf'] ?? false) {
                    $this->assertContains($actualValue, $oneOf);
                }
                if ($sameAs = $ruleOptions['sameAs'] ?? false) {
                    $this->assertSame($sameAs, $actualValue);
                }
                if (in_array('dateISO8601', $ruleOptions, true)) {
                    $this->assertMatchesRegularExpression(
                        '/^\d{4}-\d{2}-\d{2}/',
                        $actualValue ?: '',
                        "{'$ruleKey}' not a date, entry " . json_encode($row)
                    );
                }
                if (in_array('notNull', $ruleOptions, true)) {
                    $this->assertNotNull(
                        $actualValue,
                        "{'$ruleKey}' key unexpectedly empty, entry " . json_encode($row)
                    );
                }
                if (in_array('notEmpty', $ruleOptions, true)) {
                    $this->assertNotEmpty(
                        $actualValue,
                        "{'$ruleKey}' key unexpectedly null, entry " . json_encode($row)
                    );
                }
                if (in_array('uuid', $ruleOptions, true)) {
                    $this->assertMatchesRegularExpression(
                        '/^\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/',
                        $actualValue ?: '',
                        "{'$ruleKey}' key does not seem a UUID, entry: " . json_encode($row)
                    );
                }
            }
        }
    }
}