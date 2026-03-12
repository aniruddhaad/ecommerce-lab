<?php

namespace Contracts\Command;

class ReleaseInventory
{
    public function __construct(
        public readonly string $orderId
    ) {}
}