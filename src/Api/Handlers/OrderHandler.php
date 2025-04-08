<?php

namespace Barronhsu15\InterviewTintint\Api\Handlers;

use Barronhsu15\InterviewTintint\Api\Requests\GetOrdersByDatetimeAndCategoryRequest;
use Barronhsu15\InterviewTintint\Exceptions\DatabaseException;
use Barronhsu15\InterviewTintint\Exceptions\FormException;
use Barronhsu15\InterviewTintint\Order\OrderService;
use Psr\Http\Message\ResponseInterface;

/**
 * 訂單流程
 */
class OrderHandler
{
    use HandlerTrait;

    /**
     * 透過起訖日期時間和類別過濾取得訂單
     *
     * @param OrderService $orderService
     * @return ResponseInterface
     */
    public function getOrdersByDatetimeAndCategory(
        OrderService $orderService,
    ): ResponseInterface {
        try {
            $request = GetOrdersByDatetimeAndCategoryRequest::fromRequest($this->request);

            $this->responseBody->setData([
                'orders' => array_map(function ($order) {
                    return [
                        'order_id' => $order->orderId,
                        'order_date' => $order->orderDate->format('Y-m-d H:i:s'),
                        'total_amount' => $order->totalAmount,
                        'categories_summary' => array_values(array_reduce($order->orderItems, function (
                            $accumulator,
                            $orderItem
                        ) {
                            $category = $orderItem->category;
                            $quantity = ($accumulator[$category]['quantity'] ?? 0) + $orderItem->quantity;
                            $amount = ($accumulator[$category]['amount'] ?? 0) + $orderItem->quantity * $orderItem->price;

                            return array_replace($accumulator, [
                                $category => [
                                    'category' => $category,
                                    'quantity' => $quantity,
                                    'amount' => $amount,
                                ],
                            ]);
                        }, [])),
                    ];
                }, $orderService->getOrdersByDatetimeAndCategory($request->from, $request->to, $request->category)),
            ]);
        } catch (FormException $e) {
            $this->setStatusCode(400);
            $this->responseBody->handleException($e);
        } catch (DatabaseException $e) {
            $this->setStatusCode(500);
            $this->responseBody->handleException($e);
        }

        return $this->getResponse();
    }
}
