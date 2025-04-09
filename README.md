# interview-tintint

## 題目

### 題目 1:動態訂單匯總 API

設計一個 RESTful API,根據用戶輸入的時間範圍(例如 start_date 和 end_date)與產品類別(例如 category),返回該期間內所有訂單的匯總資訊。假設資料庫有以下結構:

* Orders:order_id, order_date, total_amount

* Order_Items:item_id, order_id, product_name, category, quantity, price

要求:

1. 設計 API 規格(endpoint、方法、參數、回傳格式)。

2. 用你熟悉的語言實現,需包含:

   查詢時間範圍內的訂單。

   按 category 過濾並計算每個類別的總數量與總金額。

   處理無效日期格式(例如 "2025-13-01")與空結果。

3. 實作時模擬資料庫查詢(可以用記憶體中的資料結構代替),並添加日誌記錄每次查詢的參數與結果筆數。

4. 說明如何應對高並發請求(例如每秒 1000 次)。

### 題目 2:訂單資料一致性檢查

給定一組訂單資料(格式同上),設計一個函數檢查以下一致性:

* 每個 Order_Items 的 price * quantity 總和是否等於 Orders 的 total_amount。

* order_date 是否晚於所有相關 Order_Items 的隱含創建時間(假設項目創建時間不可晚於訂單時間)。

要求:

1. 用程式碼實現,處理至少 3 種異常情況(例如負價格、空項目、日期不符)。

2. 提供至少 5 組測試資料,涵蓋正常與異常案例,並展示執行結果。

3. 說明如何將此檢查整合進 API 流程中。

## 答案

### 如何應對高並發請求(例如每秒 1000 次)

1. 資料表索引

   `orders` 資料表 `order_id` 主鍵，`order_date` 索引

   `order_items` 資料表 `item_id`，主鍵，`order_id`, `category` 索引

2. 資料查詢

   確保 SQL 正確使用索引以及合理的查詢成本

   確保沒有 N+1 問題

3. 讀寫分離/主從式架構

   擴展 slave 提供應用程式查詢

   以分散資料庫負載

4. 快取

   該案例由於查詢結果變動性極高

   並且要求訂單資料即時性

   應不適合使用快取

### 如何將檢查整合進 API 流程中

首先訂單資料理論上應不可出現錯誤，

除非成立訂單時因 BUG 導致該錯誤發生。

倘若 BUG 已修復但期間有部分訂單已成立且發生錯誤則採用方法一:

1. 檢查 BUG 版本生產期間所成立的所有訂單

2. 手動修復錯誤訂單

倘若 BUG 無法修復則採用方法二:

1. 成立訂單 API 發布 Pub/Sub 成立訂單事件

2. 後續流程使用 Pub/Sub 訂閱成立訂單事件取得訂單資料 (或取得訂單編號後重新查詢取得訂單資料)

3. 取得訂單資料後進入檢查流程

4. 發生錯誤時進入修復流程或通知開發人員

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
