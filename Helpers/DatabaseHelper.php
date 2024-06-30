<?php

namespace Helpers;

require_once("Database/MySQLWrapper.php");

use Database\MySQLWrapper;
use Exception;

class DatabaseHelper
{
    public static function getSnippetByUniqueString(string $uniqueString): array {
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM snippets WHERE unique_string = ?");
        $stmt->bind_param('s', $uniqueString);
        $stmt->execute();

        $result = $stmt->get_result();
        $snippet = $result->fetch_assoc();

        if (!$snippet) throw new Exception('Could not find a single snippet in database');

        return $snippet;
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

    public static function getTotalSnippetCount(): int {
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM snippets");
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'];
    }  

    public static function saveSnippet(array $snippetData): string {
        $db = new MySQLWrapper();
        $maxRetries = 5; // 最大試行回数
        $attempts = 0;
        // for the Error: Duplicate entry
        while ($attempts < $maxRetries) {
            $snippetData['unique_string'] = hash('md5', random_bytes(16)); // 32文字のハッシュを生成

            $stmt = $db->prepare("
                INSERT INTO snippets (snippet_name, snippet, validity_period, programming_language, unique_string)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('sssss', 
                $snippetData['snippet_name'], 
                $snippetData['snippet'], 
                $snippetData['validity_period'], 
                $snippetData['programming_language'], 
                $snippetData['unique_string']
            );

            if ($stmt->execute()) {
                error_log("Snippet saved with unique_string: " . $snippetData['unique_string']);
                return $snippetData['unique_string'];
            }

            if ($stmt->errno !== 1062) { // 1062は重複エラーのコード
                throw new Exception('Failed to save snippet: ' . $stmt->error);
            }

            $attempts++;
        }

        throw new Exception('Failed to generate a unique string after multiple attempts');
    }

    // (一覧表示時に)スニペットの有効期限をチェックする
    public static function checkAndUpdateSnippetExpiration(array &$snippet): void {
        $currentTime = new \DateTime();
        $createdAt = new \DateTime($snippet['created_at']);
    
        switch ($snippet['validity_period']) {
            case '10_minutes':
                $expirationTime = $createdAt->modify('+10 minutes');
                break;
            case '1_hour':
                $expirationTime = $createdAt->modify('+1 hour');
                break;
            case '1_day':
                $expirationTime = $createdAt->modify('+1 day');
                break;
            case 'permanent':
                return; // 永続の場合は何もしない
            default:
                throw new \Exception('Invalid validity period');
        }
    
        if ($currentTime > $expirationTime) {
            // スニペットの内容を "Expired Snippet" に更新
            $snippet['snippet'] = 'Expired Snippet';
            // データベースを更新
            $db = new MySQLWrapper();
            $stmt = $db->prepare("UPDATE snippets SET snippet = ? WHERE id = ?");
            $stmt->bind_param('si', $snippet['snippet'], $snippet['id']);
            $stmt->execute();
        }
    }
    

    // コメント: getSnippetById メソッドは現在使用されていませんが、将来的にIDベースのクエリが必要になる場合を考慮して残しています。
}