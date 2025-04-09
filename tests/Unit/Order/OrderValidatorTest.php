<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Order;

use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\Order\AmountMismatchException;
use Barronhsu15\InterviewTintint\Exceptions\Order\DatetimeMismatchException;
use Barronhsu15\InterviewTintint\Exceptions\Order\EmptyItemsException;
use Barronhsu15\InterviewTintint\Exceptions\Order\ItemNegativeAmountException;
use Barronhsu15\InterviewTintint\Exceptions\Order\OrderNegativeAmountException;
use Barronhsu15\InterviewTintint\Order\Order;
use Barronhsu15\InterviewTintint\Order\OrderItem;
use Barronhsu15\InterviewTintint\Order\OrderValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OrderValidatorTest extends TestCase
{
    public function testCheckReturnTrue(): void
    {
        $datetime = new \DateTimeImmutable();

        $this->assertTrue(OrderValidator::check(new Order('order_id', $datetime, 123, [
            new OrderItem(
                'item_id',
                'order_id',
                'product_name',
                'category',
                10,
                10,
                $datetime,
            ),
            new OrderItem(
                'item_id',
                'order_id',
                'product_name',
                'category',
                1,
                23,
                $datetime,
            ),
        ])));
    }

    /**
     * @param Order $order
     * @param class-string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     */
    #[DataProvider('checkThrownExceptionProvider')]
    public function testCheckThrownException(
        Order $order,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode,
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        OrderValidator::check($order);
    }

    public static function checkThrownExceptionProvider(): array
    {
        $datetime = new \DateTimeImmutable('@1');

        return [
            '空項目' => [
                new Order('order_id', $datetime, 123, []),
                EmptyItemsException::class,
                ExceptionMessage::OrderEmptyItem->value,
                ExceptionCode::OrderEmptyItem->value,
            ],
            '訂單負價格' => [
                new Order('order_id', $datetime, -123, [
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        10,
                        10,
                        $datetime,
                    ),
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        1,
                        23,
                        $datetime,
                    ),
                ]),
                OrderNegativeAmountException::class,
                ExceptionMessage::OrderNegativeAmount->value,
                ExceptionCode::OrderNegativeAmount->value,
            ],
            '項目負價格' => [
                new Order('order_id', $datetime, 123, [
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        1,
                        124,
                        $datetime,
                    ),
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        1,
                        -1,
                        $datetime,
                    ),
                ]),
                ItemNegativeAmountException::class,
                ExceptionMessage::OrderItemNegativeAmount->value,
                ExceptionCode::OrderItemNegativeAmount->value,
            ],
            '訂單與項目日期時間' => [
                new Order('order_id', $datetime, 123, [
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        10,
                        10,
                        $datetime,
                    ),
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        1,
                        23,
                        new \DateTimeImmutable('@0'),
                    ),
                ]),
                DatetimeMismatchException::class,
                ExceptionMessage::OrderDatetimeMismatch->value,
                ExceptionCode::OrderDatetimeMismatch->value,
            ],
            '訂單與項目金額' => [
                new Order('order_id', $datetime, 123, [
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        10,
                        10,
                        $datetime,
                    ),
                    new OrderItem(
                        'item_id',
                        'order_id',
                        'product_name',
                        'category',
                        1,
                        24,
                        $datetime,
                    ),
                ]),
                AmountMismatchException::class,
                ExceptionMessage::OrderAmountMismatch->value,
                ExceptionCode::OrderAmountMismatch->value,
            ],
        ];
    }
}
