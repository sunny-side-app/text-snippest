<?php

namespace Database\Migrations;

require_once "Database/SchemaMigration.php";

use Database\SchemaMigration;

class CreateTextSnippetTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            // snippetsテーブルの作成
            "CREATE TABLE snippets (
                id INT PRIMARY KEY AUTO_INCREMENT,
                snippet_name VARCHAR(255) NOT NULL,
                snippet TEXT NOT NULL,
                validity_period ENUM('10_minutes', '1_hour', '1_day', 'permanent') NOT NULL,
                programming_language VARCHAR(100) NOT NULL,
                unique_string VARCHAR(32) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
            // unique_stringの設定
            "UPDATE snippets SET unique_string = SUBSTRING(MD5(RAND()), 1, 32)",
            // unique制約の追加
            "ALTER TABLE snippets ADD UNIQUE (unique_string)"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE snippets"
        ];
    }
}
