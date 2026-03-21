<?php
namespace Contracts\Command;

class CreateShipment
{
    public function __construct(
        public string $orderId
    ) {}
}