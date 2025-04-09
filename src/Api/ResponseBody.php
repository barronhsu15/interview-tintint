<?php

namespace Barronhsu15\InterviewTintint\Api;

/**
 * 回應內容
 */
class ResponseBody
{
    /**
     * @param int $code 狀態碼
     * @param string $message 訊息
     * @param mixed $data 資料
     */
    public function __construct(
        private int $code = 0,
        private string $message = '',
        private mixed $data = null,
    ) {
    }

    /**
     * 輸出為陣列
     *
     * @return array{
     *  code: int,
     *  message: string,
     *  data: mixed,
     * }
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * 輸出為 JSON
     *
     * @return string JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * 設置狀態碼
     *
     * @param int $code
     * @return self
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * 設置訊息
     *
     * @param string $message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * 設置資訊
     *
     * @param mixed $data
     * @return self
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 處理例外
     *
     * @param \Throwable $e
     * @param mixed $data
     * @return self
     */
    public function handleException(\Throwable $e, mixed $data = null): self
    {
        $this->setCode($e->getCode())->setMessage($e->getMessage())->setData($data);

        return $this;
    }
}
