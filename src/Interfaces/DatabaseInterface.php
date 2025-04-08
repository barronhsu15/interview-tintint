<?php

namespace Barronhsu15\InterviewTintint\Interfaces;

/**
 * 資料庫
 */
interface DatabaseInterface
{
    /**
     * 查詢
     *
     * @param string $sql
     * @param array<int|string, mixed> $parameters
     * @return array<int, array<string, mixed>>
     *
     * @throws DatabaseExceptionInterface
     */
    public function select(string $sql, array $parameters = []): array;

    /**
     * 寫入
     *
     * @param string $sql
     * @param array<int|string, mixed> $parameters
     * @return int
     *
     * @throws DatabaseExceptionInterface
     */
    public function insert(string $sql, array $parameters = []): int;

    /**
     * 更新
     *
     * @param string $sql
     * @param array<int|string, mixed> $parameters
     * @return int
     *
     * @throws DatabaseExceptionInterface
     */
    public function update(string $sql, array $parameters = []): int;

    /**
     * 刪除
     *
     * @param string $sql
     * @param array<int|string, mixed> $parameters
     * @return int
     *
     * @throws DatabaseExceptionInterface
     */
    public function delete(string $sql, array $parameters = []): int;

    /**
     * 交易
     * 全部成功時自動提交
     * 任一失敗時自動回滾
     *
     * @param \Closure(self $db): void $callback
     *
     * @throws DatabaseExceptionInterface
     */
    public function transaction(\Closure $callback): void;

    /**
     * 開始交易
     *
     * @throws DatabaseExceptionInterface
     */
    public function beginTransaction(): void;

    /**
     * 提交
     *
     * @throws DatabaseExceptionInterface
     */
    public function commit(): void;

    /**
     * 回滾
     *
     * @throws DatabaseExceptionInterface
     */
    public function rollback(): void;
}
