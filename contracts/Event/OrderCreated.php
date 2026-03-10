<?php

namespace Contracts\Event;

class OrderCreated
{
    public function __construct(
        public readonly string $messageId,
        public readonly string $sagaId,
        public readonly string $orderId,
        public readonly array $items,
        public readonly float $totalAmount
    ) {}
}
