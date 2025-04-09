<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Api\Requests;

use Barronhsu15\InterviewTintint\Api\Requests\GetOrdersByDatetimeAndCategoryRequest;
use Barronhsu15\InterviewTintint\Enums\ExceptionCode;
use Barronhsu15\InterviewTintint\Enums\ExceptionMessage;
use Barronhsu15\InterviewTintint\Exceptions\FormException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Barronhsu15\InterviewTintint\Api\Requests\GetOrdersByDatetimeAndCategoryRequest
 */
class GetOrdersByDatetimeAndCategoryRequestTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var ServerRequestInterface&MockInterface
     */
    private ServerRequestInterface&MockInterface $serverRequest;

    protected function setUp(): void
    {
        $this->serverRequest = Mockery::mock(ServerRequestInterface::class);
    }

    public function testFromRequestReturnValue(): void
    {
        $this->serverRequest->shouldReceive('getQueryParams')->andReturn([
            'from' => '2025-01-01 01:23:45',
            'to' => '2025-01-31 02:34:56',
            'category' => 'category',
        ]);

        $request = GetOrdersByDatetimeAndCategoryRequest::fromRequest($this->serverRequest);

        $this->assertEquals(new \DateTimeImmutable('2025-01-01 01:23:45'), $request->from);
        $this->assertEquals(new \DateTimeImmutable('2025-01-31 02:34:56'), $request->to);
        $this->assertSame('category', $request->category);
    }

    public function testFromRequestMissingFieldToThrownException(): void
    {
        $this->serverRequest->shouldReceive('getQueryParams')->andReturn([]);

        $this->expectException(FormException::class);
        $this->expectExceptionMessage(ExceptionMessage::FormMissingField->value);
        $this->expectExceptionCode(ExceptionCode::FormMissingField->value);

        GetOrdersByDatetimeAndCategoryRequest::fromRequest($this->serverRequest);
    }

    public function testFromRequestInvalidFormatToThrownException(): void
    {
        $this->serverRequest->shouldReceive('getQueryParams')->andReturn([
            'from' => '2025-01-01 01:23:45',
            'to' => '2025-01-32 02:34:56',
            'category' => 'category',
        ]);

        $this->expectException(FormException::class);
        $this->expectExceptionMessage(ExceptionMessage::FormInvalidFormat->value);
        $this->expectExceptionCode(ExceptionCode::FormInvalidFormat->value);

        GetOrdersByDatetimeAndCategoryRequest::fromRequest($this->serverRequest);
    }

    public function testProperties(): void
    {
        $from = new \DateTimeImmutable();
        $to = new \DateTimeImmutable();

        $request = new GetOrdersByDatetimeAndCategoryRequest($from, $to, 'category');

        $this->assertSame($from, $request->from);
        $this->assertSame($to, $request->to);
        $this->assertSame('category', $request->category);
    }
}
