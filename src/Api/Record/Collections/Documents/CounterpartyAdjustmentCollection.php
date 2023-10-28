<?php

declare(strict_types=1);

namespace Evgeek\Moysklad\Api\Record\Collections\Documents;

use Evgeek\Moysklad\Api\Record\Collections\AbstractConcreteCollection;
use Evgeek\Moysklad\Api\Record\Objects\Documents\CounterpartyAdjustment;
use Evgeek\Moysklad\Dictionaries\Segment;
use Evgeek\Moysklad\Dictionaries\Type;

/**
 * Коллекция Корректировок баланса контрагента
 *
 * @see https://dev.moysklad.ru/doc/api/remap/1.2/documents/#dokumenty-korrektirowka-balansa-kontragenta
 *
 * @implements AbstractConcreteCollection<CounterpartyAdjustment>
 */
class CounterpartyAdjustmentCollection extends AbstractDocumentCollection
{
    public const PATH = [
        Segment::ENTITY,
        Segment::COUNTERPARTYADJUSTMENT,
    ];
    public const TYPE = Type::COUNTERPARTYADJUSTMENT;
}
