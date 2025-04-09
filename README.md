# interview-tintint

## 開發指南

### 啟動本地開發環境

```shell
docker-compose up -d
```

### 進入容器環境

```shell
docker-compose exec -it php sh
```

### 安裝 PHP 依賴套件

```shell
composer install
```

### 執行單元測試

```shell
./vendor/bin/phpunit
```
