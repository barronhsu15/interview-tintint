<?php

namespace Barronhsu15\InterviewTintint\Order;

use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\Order\AmountMismatchException;
use Barronhsu15\InterviewTintint\Exceptions\Order\DatetimeMismatchException;
use Barronhsu15\InterviewTintint\Exceptions\Order\EmptyItemsException;
use Barronhsu15\InterviewTintint\Exceptions\Order\ItemNegativeAmountException;
use Barronhsu15\InterviewTintint\Exceptions\Order\OrderNegativeAmountException;

/**
 * 訂單驗證
 */
class OrderValidator
{
    /**
     * 檢查訂單
     * 檢查空項目, 訂單負價格, 項目負價格, 訂單與項目日期時間, 訂單與項目金額
     *
     * @param Order $order
     * @return bool
     */
    public static function check(Order $order): bool
    {
        if (empty($order->orderItems)) {
            throw new EmptyItemsException(
                ExceptionMessage::OrderEmptyItem->value,
                ExceptionCode::OrderEmptyItem->value,
            );
        }

        if ($order->totalAmount < 0) {
            throw new OrderNegativeAmountException(
                ExceptionMessage::OrderNegativeAmount->value,
                ExceptionCode::OrderNegativeAmount->value,
            );
        }

        $orderItemsTotalAmount = 0;

        foreach ($order->orderItems as $orderItem) {
            if ($orderItem->price < 0) {
                throw new ItemNegativeAmountException(
                    ExceptionMessage::OrderItemNegativeAmount->value,
                    ExceptionCode::OrderItemNegativeAmount->value,
                );
            }

            if ($orderItem->createAt < $order->orderDate) {
                throw new DatetimeMismatchException(
                    ExceptionMessage::OrderDatetimeMismatch->value,
                    ExceptionCode::OrderDatetimeMismatch->value,
                );
            }

            $orderItemsTotalAmount += $orderItem->price * $orderItem->quantity;
        }

        if ($orderItemsTotalAmount !== $order->totalAmount) {
            throw new AmountMismatchException(
                ExceptionMessage::OrderAmountMismatch->value,
                ExceptionCode::OrderAmountMismatch->value,
            );
        }

        return true;
    }
}
