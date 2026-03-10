<?php

namespace Contracts\Event;

class InventoryReserved
{
    public function __construct(
        public readonly string $orderId,
        public readonly array $items
    ) {}
}