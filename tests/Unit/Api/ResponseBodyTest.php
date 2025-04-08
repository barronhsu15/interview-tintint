<?php

namespace Barronhsu15\InterviewTintint\Tests\Unit\Api;

use Barronhsu15\InterviewTintint\Api\ResponseBody;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Barronhsu15\InterviewTintint\Api\ResponseBody
 */
class ResponseBodyTest extends TestCase
{
    public function testToArrayReturnValue(): void
    {
        $this->assertSame([
            'code' => 111,
            'message' => 'xyz',
            'data' => 222,
        ], (new ResponseBody(111, 'xyz', 222))->toArray());
    }

    public function testToJsonReturnValue(): void
    {
        $this->assertSame(json_encode([
            'code' => 111,
            'message' => 'xyz',
            'data' => 222,
        ]), (new ResponseBody(111, 'xyz', 222))->toJson());
    }

    public function testSetCode(): void
    {
        $body = new ResponseBody();

        $this->assertInstanceOf(ResponseBody::class, $body->setCode(111));
        $this->assertSame(111, $body->toArray()['code']);
    }

    public function testSetMessage(): void
    {
        $body = new ResponseBody();

        $this->assertInstanceOf(ResponseBody::class, $body->setMessage('xyz'));
        $this->assertSame('xyz', $body->toArray()['message']);
    }

    public function testSetData(): void
    {
        $body = new ResponseBody();

        $this->assertInstanceOf(ResponseBody::class, $body->setData('abc'));
        $this->assertSame('abc', $body->toArray()['data']);
    }

    public function testHandleException(): void
    {
        $body = new ResponseBody();
        $e = new \Exception('xyz', 111);

        $this->assertInstanceOf(ResponseBody::class, $body->handleException($e, 222));
        $this->assertSame($body->toArray(), [
            'code' => 111,
            'message' => 'xyz',
            'data' => 222,
        ]);
    }
}
