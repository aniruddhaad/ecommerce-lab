<?php
namespace Contracts\Event;

class ShipmentCreated
{
    public function __construct(
        public string $orderId
    ) {}
}