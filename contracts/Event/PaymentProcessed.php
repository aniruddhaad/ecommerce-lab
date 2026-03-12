<?php
namespace Contracts\Event;

class PaymentProcessed
{
    public function __construct(
        public readonly string $orderId
    ) {}
}