<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Order;

use Barronhsu15\InterviewTintint\Interfaces\DatabaseExceptionInterface;
use Barronhsu15\InterviewTintint\Interfaces\DatabaseInterface;
use Barronhsu15\InterviewTintint\Order\Order;
use Barronhsu15\InterviewTintint\Order\OrderItem;
use Barronhsu15\InterviewTintint\Order\OrderRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Barronhsu15\InterviewTintint\Order\OrderRepository
 */
class OrderRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var DatabaseInterface&MockInterface
     */
    private DatabaseInterface&MockInterface $db;

    /**
     * @var LoggerInterface&MockInterface
     */
    private LoggerInterface&MockInterface $logger;

    /**
     * @var OrderRepository
     */
    private OrderRepository $repository;

    protected function setUp(): void
    {
        $this->db = Mockery::spy(DatabaseInterface::class);
        $this->logger = Mockery::spy(LoggerInterface::class);
        $this->repository = new OrderRepository($this->db, $this->logger);
    }

    public function testGetOrdersByDatetimeAndCategoryReturnValue(): void
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

        $from = new \DateTimeImmutable('2025-01-01 00:00:00');
        $to = new \DateTimeImmutable('2025-01-31 23:59:59');

        $rows = [
            $createRow('order_id_1', '2025-01-01 01:23:45', 123, 'item_id_1', 'product_name_1', 'category_1', 1, 123),
            $createRow('order_id_2', '2025-01-02 02:34:56', 456, 'item_id_2', 'product_name_1', 'category_1', 2, 123),
            $createRow('order_id_2', '2025-01-02 02:34:56', 456, 'item_id_3', 'product_name_2', 'category_2', 3, 70),
        ];

        $this->db->shouldReceive('select')->andReturn($rows);

        $this->assertEquals([
            new Order('order_id_1', new \DateTimeImmutable('2025-01-01 01:23:45'), 123, [
                new OrderItem('item_id_1', 'order_id_1', 'product_name_1', 'category_1', 1, 123),
            ]),
            new Order('order_id_2', new \DateTimeImmutable('2025-01-02 02:34:56'), 456, [
                new OrderItem('item_id_2', 'order_id_2', 'product_name_1', 'category_1', 2, 123),
                new OrderItem('item_id_3', 'order_id_2', 'product_name_2', 'category_2', 3, 70),
            ]),
        ], $this->repository->getOrdersByDatetimeAndCategory($from, $to, 'category'));

        $this->db->shouldHaveReceived('select')->with(Mockery::type('string'), [
            ':datetime_from' => '2025-01-01 00:00:00',
            ':datetime_to' => '2025-01-31 23:59:59',
            ':category' => 'category'
        ])->once();

        $this->logger->shouldHaveReceived('info')->with(Mockery::type('string'), [
            'function' => 'getOrdersByDatetimeAndCategory',
            'from' => $from,
            'to' => $to,
            'category' => 'category',
        ])->once();

        $this->logger->shouldHaveReceived('info')->with(Mockery::type('string'), [
            'function' => 'getOrdersByDatetimeAndCategory',
            'rows_count' => count($rows),
        ])->once();
    }

    public function testGetOrdersByDatetimeAndCategoryThrownException(): void
    {
        $e = new class() extends \Exception implements DatabaseExceptionInterface {};

        $this->db->shouldReceive('select')->andThrow($e);

        $this->expectException(DatabaseExceptionInterface::class);

        $this->repository->getOrdersByDatetimeAndCategory(
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            'category',
        );

        $this->logger->shouldHaveReceived('error')->with(Mockery::type('string'), [
            'function' => 'getOrdersByDatetime',
            'exception' => $e,
        ])->once();
    }
}
