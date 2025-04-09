<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Order;

use Barronhsu15\InterviewTintint\Order\Order;
use Barronhsu15\InterviewTintint\Order\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Barronhsu15\InterviewTintint\Order\Order
 */
class OrderTest extends TestCase
{
    public function testProperties(): void
    {
        $orderDate = new \DateTimeImmutable();
        $orderItems = [new OrderItem('item_id', 'order_id', 'product_name', 'category', 123, 456, new \DateTimeImmutable())];

        $order = new Order('order_id', $orderDate, 123, $orderItems);

        $this->assertSame('order_id', $order->orderId);
        $this->assertSame($orderDate, $order->orderDate);
        $this->assertSame(123, $order->totalAmount);
        $this->assertSame($orderItems, $order->orderItems);
    }
}
