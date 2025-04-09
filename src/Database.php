<?php

namespace Barronhsu15\InterviewTintint;

use Barronhsu15\InterviewTintint\Interfaces\DatabaseInterface;

/**
 * @codeCoverageIgnore
 */
class Database implements DatabaseInterface
{
    public function select(string $sql, array $parameters = []): array
    {
        $createRow = function (
            string $orderId,
            string $orderDate,
            int $totalAmount,
            string $itemId,
            string $productName,
            string $category,
            int $quantity,
            int $price,
        ) {
            return [
                'order_id' => $orderId,
                'order_date' => $orderDate,
                'total_amount' => $totalAmount,
                'item_id' => $itemId,
                'product_name' => $productName,
                'category' => $category,
                'quantity' => $quantity,
                'price' => $price,
            ];
        };

        return [
            $createRow('order_id_1', '2025-01-01 01:23:45', 123, 'item_id_1', 'product_name_1', 'category_1', 1, 123),
            $createRow('order_id_2', '2025-01-02 02:34:56', 456, 'item_id_2', 'product_name_1', 'category_1', 2, 123),
            $createRow('order_id_2', '2025-01-02 02:34:56', 456, 'item_id_3', 'product_name_2', 'category_2', 3, 70),
        ];
    }

    public function insert(string $sql, array $parameters = []): int
    {
        throw new DatabaseException();
    }

    public function update(string $sql, array $parameters = []): int
    {
        throw new DatabaseException();
    }

    public function delete(string $sql, array $parameters = []): int
    {
        throw new DatabaseException();
    }

    public function transaction(\Closure $callback): void
    {
        throw new DatabaseException();
    }

    public function beginTransaction(): void
    {
        throw new DatabaseException();
    }

    public function commit(): void
    {
        throw new DatabaseException();
    }

    public function rollback(): void
    {
        throw new DatabaseException();
    }
}
