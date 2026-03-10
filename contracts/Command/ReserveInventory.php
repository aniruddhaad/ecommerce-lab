<?php

namespace Contracts\Command;

class ReserveInventory
{
    public function __construct(
        public readonly string $orderId,
        public readonly array $items
    ) {}
}
