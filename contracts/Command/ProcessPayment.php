<?php
namespace Contracts\Command;

class ProcessPayment
{
    public function __construct(
        public readonly string $orderId,
        public readonly float $amount
    ) {}
}