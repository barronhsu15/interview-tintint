<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Api\Handlers;

use Barronhsu15\InterviewTintint\Api\Handlers\OrderHandler;
use Barronhsu15\InterviewTintint\Api\ResponseBody;
use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\DatabaseException;
use Barronhsu15\InterviewTintint\Order\Order;
use Barronhsu15\InterviewTintint\Order\OrderItem;
use Barronhsu15\InterviewTintint\Order\OrderService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Barronhsu15\InterviewTintint\Api\Handlers\OrderHandler
 */
class OrderHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var ServerRequestInterface&MockInterface
     */
    private ServerRequestInterface&MockInterface $request;

    /**
     * @var StreamInterface&MockInterface
     */
    private StreamInterface&MockInterface $stream;

    /**
     * @var ResponseInterface&MockInterface
     */
    private ResponseInterface&MockInterface $response;

    /**
     * @var ResponseBody
     */
    private ResponseBody $responseBody;

    /**
     * @var OrderService&MockInterface
     */
    private OrderService&MockInterface $orderService;

    /**
     * @var OrderHandler
     */
    private OrderHandler $handler;

    protected function setUp(): void
    {
        $this->request = Mockery::mock(ServerRequestInterface::class);

        $this->stream = Mockery::spy(StreamInterface::class);

        $this->response = Mockery::spy(ResponseInterface::class);
        $this->response->shouldReceive('withStatus')->andReturnSelf();
        $this->response->shouldReceive('getBody')->andReturn($this->stream);

        $this->responseBody = new ResponseBody();

        $this->orderService = Mockery::mock(OrderService::class);

        $this->handler = new OrderHandler($this->request, $this->response, $this->responseBody);
    }

    public function testGetOrdersByDatetimeResponseOrderData(): void
    {
        $createOrderData = function (
            string $orderId,
            string $orderDate,
            int $totalAmount,
            array $categoriesSummary,
        ) {
            return [
                'order_id' => $orderId,
                'order_date' => $orderDate,
                'total_amount' => $totalAmount,
                'categories_summary' => $categoriesSummary,
            ];
        };

        $createCategorySummaryData = function (
            string $category,
            int $quantity,
            int $amount,
        ) {
            return [
                'category' => $category,
                'quantity' => $quantity,
                'amount' => $amount,
            ];
        };

        $this->request->shouldReceive('getQueryParams')->andReturn([
            'from' => '2025-01-01',
            'to' => '2025-01-31',
            'category' => 'category_1',
        ]);

        $this->orderService->shouldReceive('getOrdersByDatetimeAndCategory')->andReturn([
            new Order('order_id_1', new \DateTimeImmutable('2025-01-01 01:23:45'), 123, [
                new OrderItem('item_id_1', 'order_id_1', 'product_name_1', 'category_1', 1, 123, new \DateTimeImmutable('2025-01-01 01:23:45')),
            ]),
            new Order('order_id_2', new \DateTimeImmutable('2025-01-02 02:34:56'), 456, [
                new OrderItem('item_id_2', 'order_id_2', 'product_name_2', 'category_1', 2, 123, new \DateTimeImmutable('2025-01-02 02:34:56')),
                new OrderItem('item_id_3', 'order_id_2', 'product_name_3', 'category_1', 3, 70, new \DateTimeImmutable('2025-01-02 02:34:56')),
            ]),
            new Order('order_id_3', new \DateTimeImmutable('2025-01-03 03:45:00'), 789, [
                new OrderItem('item_id_4', 'order_id_3', 'product_name_4', 'category_1', 4, 123, new \DateTimeImmutable('2025-01-03 03:45:00')),
                new OrderItem('item_id_5', 'order_id_3', 'product_name_5', 'category_2', 5, 21, new \DateTimeImmutable('2025-01-03 03:45:00')),
                new OrderItem('item_id_6', 'order_id_3', 'product_name_6', 'category_2', 6, 32, new \DateTimeImmutable('2025-01-03 03:45:00')),
            ]),
        ]);

        $this->handler->getOrdersByDatetimeAndCategory($this->orderService);

        $this->response->shouldNotHaveReceived('withStatus');

        $this->stream->shouldHaveReceived('write')->with(json_encode([
            'code' => 0,
            'message' => '',
            'data' => [
                'orders' => [
                    $createOrderData('order_id_1', '2025-01-01 01:23:45', 123, [
                        $createCategorySummaryData('category_1', 1, 123),
                    ]),
                    $createOrderData('order_id_2', '2025-01-02 02:34:56', 456, [
                        $createCategorySummaryData('category_1', 5, 456),
                    ]),
                    $createOrderData('order_id_3', '2025-01-03 03:45:00', 789, [
                        $createCategorySummaryData('category_1', 4, 492),
                        $createCategorySummaryData('category_2', 11, 297),
                    ]),
                ],
            ],
        ]))->once();
    }

    public function testGetOrdersByDatetimeResponseFormErrorData(): void
    {
        $this->request->shouldReceive('getQueryParams')->andReturn([]);

        $this->handler->getOrdersByDatetimeAndCategory($this->orderService);

        $this->response->shouldHaveReceived('withStatus')->with(400, '')->once();

        $this->stream->shouldHaveReceived('write')->with(json_encode([
            'code' => ExceptionCode::FormMissingField->value,
            'message' => ExceptionMessage::FormMissingField->value,
            'data' => null,
        ]))->once();
    }

    public function testGetOrdersByDatetimeResponseDatabaseErrorData(): void
    {
        $this->request->shouldReceive('getQueryParams')->andReturn([
            'from' => '2025-01-01',
            'to' => '2025-01-31',
            'category' => 'category_1',
        ]);

        $this->orderService->shouldReceive('getOrdersByDatetimeAndCategory')->andThrow(new DatabaseException(
            ExceptionMessage::DatabaseFailed->value,
            ExceptionCode::DatabaseFailed->value,
        ));

        $this->handler->getOrdersByDatetimeAndCategory($this->orderService);

        $this->response->shouldHaveReceived('withStatus')->with(500, '')->once();

        $this->stream->shouldHaveReceived('write')->with(json_encode([
            'code' => ExceptionCode::DatabaseFailed->value,
            'message' => ExceptionMessage::DatabaseFailed->value,
            'data' => null,
        ]))->once();
    }
}
