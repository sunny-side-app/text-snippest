<?php

namespace Helpers;

require_once("Database/MySQLWrapper.php");

use Database\MySQLWrapper;
use Exception;

class DatabaseHelper
{
    public static function saveSnippet($snippetName, $snippetContent, $validityPeriod, $programmingLanguage): int {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("INSERT INTO snippets (snippet_name, snippet, validity_period, programming_language) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $snippetName, $snippetContent, $validityPeriod, $programmingLanguage);
        $stmt->execute();

        return $db->insert_id; // 保存したスニペットのIDを返す
    }
    
    public static function getSnippetById(int $id): array{
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM snippets WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $snippet = $result->fetch_assoc();

        if (!$snippet) throw new Exception('Could not find a single snippet in database');

        return $snippet;
    }

    public static function getSnippets(int $page, int $perPage): array{
        $db = new MySQLWrapper();

        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT * FROM snippets LIMIT ? OFFSET ?");
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        // fetch_all(): 連想配列の配列として全行を返すため、指定された条件に合致する複数のレコードを取得する場合に適している
        $snippets = $result->fetch_all(MYSQLI_ASSOC);

        return $snippets; // 空のリストが返されることを許容
    }

    
}