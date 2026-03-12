<?php
namespace Contracts\Event;
class PaymentFailed
{
    public function __construct(
        public readonly string $orderId
    ) {}
}