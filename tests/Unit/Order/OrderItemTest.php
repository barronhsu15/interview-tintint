<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Order;

use Barronhsu15\InterviewTintint\Order\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Barronhsu15\InterviewTintint\Order\OrderItem
 */
class OrderItemTest extends TestCase
{
    public function testProperties(): void
    {
        $orderItem = new OrderItem('item_id', 'order_id', 'product_name', 'category', 123, 456);

        $this->assertSame('item_id', $orderItem->itemId);
        $this->assertSame('order_id', $orderItem->orderId);
        $this->assertSame('product_name', $orderItem->productName);
        $this->assertSame('category', $orderItem->category);
        $this->assertSame(123, $orderItem->quantity);
        $this->assertSame(456, $orderItem->price);
    }
}
