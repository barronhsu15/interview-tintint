<?php

namespace Barronhsu15\InterviewTintint\Order;

/**
 * 訂單項目
 */
class OrderItem
{
    /**
     * @param string $itemId 訂單項目 ID
     * @param string $orderId 訂單 ID
     * @param string $productName 產品名稱
     * @param string $category 類別
     * @param int $quantity 數量
     * @param int $price 售價
     * @param \DateTimeImmutable $createAt,
     */
    public function __construct(
        public readonly string $itemId,
        public readonly string $orderId,
        public readonly string $productName,
        public readonly string $category,
        public readonly int $quantity,
        public readonly int $price,
        public readonly \DateTimeImmutable $createAt,
    ) {}
}
