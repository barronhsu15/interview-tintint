<?php

namespace Barronhsu15\InterviewTintint\Order;

use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\DatabaseException;
use Barronhsu15\InterviewTintint\Interfaces\DatabaseExceptionInterface;
use Barronhsu15\InterviewTintint\Order\Order;
use Barronhsu15\InterviewTintint\Order\OrderRepository;

/**
 * 訂單服務
 */
class OrderService
{
    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    /**
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param string $category
     * @return array<int, Order>
     *
     * @throws DatabaseException
     */
    public function getOrdersByDatetimeAndCategory(
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        string $category,
    ): array {
        try {
            return $this->orderRepository->getOrdersByDatetimeAndCategory($from, $to, $category);
        } catch (DatabaseExceptionInterface $e) {
            throw new DatabaseException(
                ExceptionMessage::DatabaseFailed->value,
                ExceptionCode::DatabaseFailed->value,
            );
        }
    }
}
