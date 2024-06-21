<?php

require_once("Helpers/DatabaseHelper.php");
require_once("Helpers/ValidationHelper.php");
require_once("Response/HTTPRenderer.php");
require_once("Response/Render/HTMLRenderer.php");
require_once("Response/Render/JSONRenderer.php");

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

$DEBUG = true;

return [
    'snippets' => function(): HTTPRenderer {
        global $DEBUG;
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_FILES['snippet'])) {
                    throw new Exception('No file uploaded');
                }

                $file = $_FILES['snippet'];
                $content = file_get_contents($file['tmp_name']);

                // Monaco EditorのWebAPIを呼び出して構文ハイライト処理を行う
                $highlightedContent = MonacoAPIHelper::highlightCode($content);

                // 保存処理（仮定としてDatabaseHelperを利用）
                $snippetId = DatabaseHelper::saveSnippet($highlightedContent);

                // 単票画面へのリダイレクト処理
                header('Location: /snippets?id=' . $snippetId);
                exit;
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['id'])) {
                    $id = ValidationHelper::integer($_GET['id']);

                    $snippet = DatabaseHelper::getSnippetById($id);
                    return new HTMLRenderer('component/snippet-detail', ['snippet' => $snippet]);
                } else {
                    $page = $_GET['page'] ?? 1;
                    $perPage = $_GET['perpage'] ?? 10;

                    // デバッグ情報の追加
                    if ($DEBUG) {
                        echo "Page: $page, PerPage: $perPage<br>";
                    }

                    $page = ValidationHelper::integer($page);
                    $perPage = ValidationHelper::integer($perPage);

                    $snippets = DatabaseHelper::getSnippets($page, $perPage);
                    return new HTMLRenderer('component/snippets-list', [
                        'page' => $page,
                        'perPage' => $perPage,
                        'snippets' => $snippets,
                    ]);
                }
            } else {
                throw new Exception('Invalid request method');
            }
        } catch (Exception $e) {
            http_response_code(500);
            if ($DEBUG) {
                echo 'Error: ' . $e->getMessage();
            }
            return new JSONRenderer(['message' => $e->getMessage()]);
        }
    },
];
