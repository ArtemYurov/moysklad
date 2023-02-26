<?php

namespace Evgeek\Tests\Unit\Services;

use Evgeek\Moysklad\Enums\HttpMethod;
use Evgeek\Moysklad\Http\Payload;
use Evgeek\Moysklad\Services\Url;
use PHPUnit\Framework\TestCase;

/** @covers \Evgeek\Moysklad\Services\Url */
class UrlTest extends TestCase
{
    private const API_URL = 'https://online.moysklad.ru/api/remap/1.2';

    /** @dataProvider payloadDataProvider */
    public function testMakeWithCorrectPayload(string $expectedPath, array $path, array $params = []): void
    {
        $payload = new Payload(HttpMethod::GET, $path, $params, []);

        $this->assertSame(self::API_URL . $expectedPath, Url::make($payload));
    }

    public function testMakeWithWrongPath(): void
    {
        $path = [['entity'], ['product']];
        $payload = new Payload(HttpMethod::GET, $path, [], []);

        $this->expectWarning();
        $this->expectWarningMessage('Array to string conversion');

        Url::make($payload);
    }

    /** @dataProvider mixedValuesDataProvider */
    public function testConvertMixedValueToString(string $expectedValue, mixed $value): void
    {
        $this->assertSame($expectedValue, Url::convertMixedValueToString($value));
    }

    private function payloadDataProvider(): array
    {
        return [
            [
                '/entity',
                ['entity'],
            ],
            [
                '/entity/product?limit=1',
                ['entity', 'product'],
                ['limit' => 1],
            ],
            [
                '/endpoint/method?filter=archived%3Dfalse%3Bname%7E%3Dor&offset=2',
                ['endpoint', 'method'],
                ['filter' => 'archived=false;name~=or', 'offset' => 2],
            ],
        ];
    }

    private function mixedValuesDataProvider(): array
    {
        return [
            ['', ''],
            ['test', 'test'],
            ['TEST', 'TEST'],
            ['TesT', 'TesT'],
            ['true', true],
            ['false', false],
            ['0', 0],
            ['1', 1],
            ['-66', -66],
            ['123', 123],
            ['1.23', 1.23],
            ['1', 1.0],
            ['-1.0001', -1.0001],
        ];
    }
}