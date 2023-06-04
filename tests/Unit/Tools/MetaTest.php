<?php

namespace Evgeek\Tests\Unit\Tools;

use Evgeek\Moysklad\Formatters\ArrayFormat;
use Evgeek\Moysklad\Formatters\StdClassFormat;
use Evgeek\Moysklad\Formatters\StringFormat;
use Evgeek\Moysklad\Services\Url;
use Evgeek\Moysklad\Tools\Meta;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/** @covers \Evgeek\Moysklad\Tools\Meta */
class MetaTest extends TestCase
{
    public function testFormatSetter(): void
    {
        Meta::setFormat(new ArrayFormat());
        $this->assertIsArray(Meta::organization('guid'));

        Meta::setFormat(new StringFormat());
        $this->assertIsString(Meta::organization('guid'));

        Meta::setFormat(new StdClassFormat());
        $this->assertInstanceOf(stdClass::class, Meta::organization('guid'));
    }

    /** @dataProvider correctPathAndTypeDataProvider */
    public function testCreateFromPathWithStringSegmentsWorks(string $expectedSegments, array $path, string $type): void
    {
        Meta::setFormat(new ArrayFormat());

        $meta = Meta::create($path, $type);
        $expected = [
            'href' => Url::API . $expectedSegments,
            'type' => $type,
            'mediaType' => 'application/json',
        ];

        $this->assertSame($expected, $meta);
    }

    public static function correctPathAndTypeDataProvider(): array
    {
        return [
            ['', [], 'type1'],
            ['/endpoint', ['endpoint'], 'type2'],
            ['/endpoint/method', ['endpoint', 'method'], 'type3'],
        ];
    }

    /** @dataProvider incorrectPathSegmentDataProvider */
    public function testCreateFromPathWithNotStringSegmentsDoesNotWorks(mixed $segment): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('1th segment of path is not a string');

        Meta::create(['endpoint', $segment], 'type');
    }

    public static function incorrectPathSegmentDataProvider(): array
    {
        return [
            [null],
            [false],
            [true],
            [0],
            [1.1],
            [['segment']],
            [new stdClass()],
        ];
    }

    public function testEntity(): void
    {
        Meta::setFormat(new ArrayFormat());

        $meta = Meta::entity(['product', 'guid'], 'product');
        $expected = [
            'href' => Url::API . '/entity/product/guid',
            'type' => 'product',
            'mediaType' => 'application/json',
        ];

        $this->assertSame($expected, $meta);
    }

    public function testState(): void
    {
        Meta::setFormat(new ArrayFormat());

        $meta = Meta::state('product', 'guid-state');
        $expected = [
            'href' => Url::API . '/entity/product/metadata/states/guid-state',
            'type' => 'state',
            'mediaType' => 'application/json',
        ];

        $this->assertSame($expected, $meta);
    }

    public function testOrganization(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'organization',
            '/entity/organization/guid1',
            'organization',
            'guid1'
        );
    }

    public function testCounterparty(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'counterparty',
            '/entity/counterparty/guid2',
            'counterparty',
            'guid2'
        );
    }

    public function testStore(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'store',
            '/entity/store/guid',
            'store',
            'guid'
        );
    }

    public function testCurrency(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'currency',
            '/entity/currency/guid',
            'currency',
            'guid'
        );
    }

    public function testSaleschannel(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'saleschannel',
            '/entity/saleschannel/guid',
            'saleschannel',
            'guid'
        );
    }

    public function testProduct(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'product',
            '/entity/product/guid',
            'product',
            'guid'
        );
    }

    public function testEmployee(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'employee',
            '/entity/employee/guid',
            'employee',
            'guid'
        );
    }

    public function testCustomerorder(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'customerorder',
            '/entity/customerorder/guid',
            'customerorder',
            'guid'
        );
    }

    public function testService(): void
    {
        $this->assertMetaMethodByGuidWorks(
            'service',
            '/entity/service/guid',
            'service',
            'guid'
        );
    }

    private function assertMetaMethodByGuidWorks(string $method, string $expectedSegment, string $expectedType, string $guid): void
    {
        Meta::setFormat(new ArrayFormat());

        $meta = Meta::{$method}($guid);
        $expected = [
            'href' => Url::API . $expectedSegment,
            'type' => $expectedType,
            'mediaType' => 'application/json',
        ];

        $this->assertSame($expected, $meta);
    }
}
