<?php
namespace Contracts\Event;

class InventoryReleased
{
    public function __construct(
        public readonly string $orderId
    ) {}
}