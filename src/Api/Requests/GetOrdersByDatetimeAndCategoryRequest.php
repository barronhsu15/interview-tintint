<?php

namespace Barronhsu15\InterviewTintint\Api\Requests;

use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\FormException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 透過起訖日期時間和分類取得訂單資料請求
 */
class GetOrdersByDatetimeAndCategoryRequest
{
    /**
     * 由請求建立實例
     *
     * @param ServerRequestInterface $request
     * @return self
     *
     * @throws FormException
     */
    public static function fromRequest(ServerRequestInterface $request): self
    {
        $queryParameters = $request->getQueryParams();
        $from = $queryParameters['from'] ?? null;
        $to = $queryParameters['to'] ?? null;
        $category = $queryParameters['category'] ?? null;

        if (!isset($from, $to, $category)) {
            throw new FormException(
                ExceptionMessage::FormMissingField->value,
                ExceptionCode::FormMissingField->value,
            );
        }

        try {
            return new self(
                new \DateTimeImmutable($from),
                new \DateTimeImmutable($to),
                $category,
            );
        } catch (\DateMalformedStringException $e) {
            throw new FormException(
                ExceptionMessage::FormInvalidFormat->value,
                ExceptionCode::FormInvalidFormat->value,
            );
        }
    }

    /**
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @param string $category
     */
    public function __construct(
        public readonly \DateTimeImmutable $from,
        public readonly \DateTimeImmutable $to,
        public readonly string $category,
    ) {}
}
