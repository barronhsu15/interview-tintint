<?php

namespace Barronhsu15\InterviewTintint;

use Psr\Log\LoggerInterface;
use Stringable;

/**
 * @codeCoverageIgnore
 */
class Logger implements LoggerInterface
{
    public function emergency(string|\Stringable $message, array $context = []): void {}
    public function alert(string|\Stringable $message, array $context = []): void {}
    public function critical(string|\Stringable $message, array $context = []): void {}
    public function error(string|\Stringable $message, array $context = []): void {}
    public function warning(string|\Stringable $message, array $context = []): void {}
    public function notice(string|\Stringable $message, array $context = []): void {}
    public function info(string|\Stringable $message, array $context = []): void {}
    public function debug(string|\Stringable $message, array $context = []): void {}
    public function log($level, string|\Stringable $message, array $context = []): void {}
}
