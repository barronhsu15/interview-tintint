<?php

namespace Barronhsu15\InterviewTintint\Order;

/**
 * 訂單
 */
class Order
{
    /**
     * @param string $orderId 訂單 ID
     * @param \DateTimeImmutable $orderDate 訂單日期
     * @param int $totalAmount 總金額
     * @param array<int, OrderItem> $orderItems 訂單項目
     */
    public function __construct(
        public readonly string $orderId,
        public readonly \DateTimeImmutable $orderDate,
        public readonly int $totalAmount,
        public readonly array $orderItems,
    ) {}
}
