<?php

namespace Barronhsu15\InterviewTintint\Order;

use Barronhsu15\InterviewTintint\Interfaces\DatabaseExceptionInterface;
use Barronhsu15\InterviewTintint\Interfaces\DatabaseInterface;
use Psr\Log\LoggerInterface;

class OrderRepository
{
    /**
     * @param DatabaseInterface $db
     * @param LoggerInterface $logger
     */
    public function __construct(
        private DatabaseInterface $db,
        private LoggerInterface $logger,
    ) {}

    /**
     * 透過起訖日期時間取得訂單
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param string $category
     * @return array<int, Order>
     *
     * @throws DatabaseExceptionInterface
     */
    public function getOrdersByDatetimeAndCategory(
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        string $category,
    ): array {
        $this->logger->info('Database query', [
            'function' => __FUNCTION__,
            'from' => $from,
            'to' => $to,
            'category' => 'category',
        ]);

        $rows = null;

        try {
            $rows = $this->selectOrderAndOrderItemRowsByDatetimeAndCategory($from, $to, $category);

            $this->logger->info('Database query result', [
                'function' => __FUNCTION__,
                'rows_count' => count($rows),
            ]);
        } catch (DatabaseInterface $e) {
            $this->logger->error('Database query failed', [
                'function' => __FUNCTION__,
                'exception' => $e,
            ]);

            throw $e;
        }

        /**
         * @var array<string, array<int, array{
         *  order_id: string,
         *  order_date: string,
         *  total_amount: int,
         *  item_id: string,
         *  product_name: string,
         *  category: string,
         *  quantity: int,
         *  price: int,
         *  item_create_at: string,
        * }>>
         */
        $rowsByOrderId = array_reduce($rows, function ($accumulator, $row) {
            $orderId = $row['order_id'];

            return array_replace($accumulator, [
                $orderId => array_merge($accumulator[$orderId] ?? [], [$row]),
            ]);
        }, []);

        return array_map(function ($rows) {
            $row = $rows[0];

            return new Order(
                $row['order_id'],
                new \DateTimeImmutable($row['order_date']),
                $row['total_amount'],
                array_map(function ($row) {
                    return new OrderItem(
                        $row['item_id'],
                        $row['order_id'],
                        $row['product_name'],
                        $row['category'],
                        $row['quantity'],
                        $row['price'],
                        new \DateTimeImmutable($row['item_create_at']),
                    );
                }, $rows),
            );
        }, array_values($rowsByOrderId));
    }

    /**
     * 透過起訖日期時間查詢訂單及訂單項目
     *
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param string $category
     * @return array<int, array{
     *  order_id: string,
     *  order_date: string,
     *  total_amount: int,
     *  item_id: string,
     *  product_name: string,
     *  category: string,
     *  quantity: int,
     *  price: int,
     *  item_create_at: string,
     * }>
     *
     * @throws DatabaseExceptionInterface
     */
    private function selectOrderAndOrderItemRowsByDatetimeAndCategory(
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        string $category,
    ): array {
        $sql = <<<SQL
        SELECT
            o1.order_id AS order_id,
            o1.order_date AS order_date,
            o1.total_amount AS total_amount,
            oi1.item_id AS item_id,
            oi1.product_name AS product_name,
            oi1.category AS category,
            oi1.quantity AS quantity,
            oi1.price AS price,
            oi1.create_at AS item_create_at
        FROM
            orders AS o1
        LEFT JOIN
            order_items AS oi1
        WHERE
            o1.order_id IN (
                SELECT
                    o2.order_id
                FROM
                    orders AS o2
                LEFT JOIN
                    order_items AS oi2
                WHERE
                    o2.order_date BETWEEN :datetime_from AND :datetime_to
                AND
                    oi2.category = :category
            )
        SQL;

        $parameters = [
            ':datetime_from' => $from->format('Y-m-d H:i:s'),
            ':datetime_to' => $to->format('Y-m-d H:i:s'),
            ':category' => $category,
        ];

        return $this->db->select($sql, $parameters);
    }
}
