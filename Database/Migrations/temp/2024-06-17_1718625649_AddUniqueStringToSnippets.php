<?php

namespace Database\Migrations;

require_once "Database/SchemaMigration.php";

use Database\SchemaMigration;

class AddUniqueStringToSnippets implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE snippets ADD COLUMN unique_string VARCHAR(32) NOT NULL AFTER programming_language",
            "UPDATE snippets SET unique_string = SUBSTRING(MD5(RAND()), 1, 32)",
            "ALTER TABLE snippets ADD UNIQUE (unique_string)",
            "ALTER TABLE snippets DROP COLUMN url"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE snippets DROP COLUMN unique_string",
            "ALTER TABLE snippets ADD COLUMN url VARCHAR(255)"
        ];
    }
}
