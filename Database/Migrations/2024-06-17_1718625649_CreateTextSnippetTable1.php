<?php

namespace Database\Migrations;

require_once "Database/SchemaMigration.php";

use Database\SchemaMigration;

class CreateTextSnippetTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE snippets (
                id INT PRIMARY KEY AUTO_INCREMENT,
                snippet_name VARCHAR(255) NOT NULL,
                snippet TEXT NOT NULL,
                validity_period ENUM('10_minutes', '1_hour', '1_day', 'permanent') NOT NULL,
                programming_language VARCHAR(100) NOT NULL,
                url VARCHAR(255),
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )"
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