<?php

namespace Barronhsu15\InterviewTintint\Api\Handlers;

use Barronhsu15\InterviewTintint\Api\ResponseBody;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait HandlerTrait
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param ResponseBody $responseBody
     */
    public function __construct(
        protected ServerRequestInterface $request,
        protected ResponseInterface $response,
        protected ResponseBody $responseBody,
    ) {}

    /**
     * 設置狀態碼
     *
     * @param int $code
     * @param string $reasonPhrase
     */
    protected function setStatusCode(int $code, string $reasonPhrase = ''): void
    {
        $this->response = $this->response->withStatus($code, $reasonPhrase);
    }

    /**
     * 取得回應
     *
     * @return ResponseInterface
     */
    protected function getResponse(): ResponseInterface
    {
        $this->response->getBody()->write($this->responseBody->toJson());

        return $this->response;
    }
}
