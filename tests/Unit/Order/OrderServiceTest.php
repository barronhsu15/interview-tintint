<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Order;

use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\DatabaseException;
use Barronhsu15\InterviewTintint\Interfaces\DatabaseExceptionInterface;
use Barronhsu15\InterviewTintint\Order\Order;
use Barronhsu15\InterviewTintint\Order\OrderRepository;
use Barronhsu15\InterviewTintint\Order\OrderService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Barronhsu15\InterviewTintint\Order\OrderService
 */
class OrderServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var OrderRepository&MockInterface
     */
    private OrderRepository&MockInterface $orderRepository;

    /**
     * @var OrderService
     */
    private $service;

    protected function setUp(): void
    {
        $this->orderRepository = Mockery::mock(OrderRepository::class);
        $this->service = new OrderService($this->orderRepository);
    }

    public function testGetOrdersByDatetimeReturnValue(): void
    {
        $orders = [
            new Order('order_id_1', new \DateTimeImmutable(), 123, []),
            new Order('order_id_2', new \DateTimeImmutable(), 456, [])
        ];

        $this->orderRepository->shouldReceive('getOrdersByDatetimeAndCategory')->andReturn($orders);

        $this->assertSame($orders, $this->service->getOrdersByDatetimeAndCategory(
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            'category',
        ));
    }

    public function testGetOrdersByDatetimeThrownException(): void
    {
        $this->orderRepository->shouldReceive('getOrdersByDatetimeAndCategory')
            ->andThrow(new class() extends \Exception implements DatabaseExceptionInterface {});

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage(ExceptionMessage::DatabaseFailed->value);
        $this->expectExceptionCode(ExceptionCode::DatabaseFailed->value);

        $this->service->getOrdersByDatetimeAndCategory(
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            'category',
        );
    }
}
