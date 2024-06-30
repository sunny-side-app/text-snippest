<?php

require_once("Helpers/DatabaseHelper.php");
require_once("Helpers/SanitizationAndValidationHelper.php");
require_once("Response/HTTPRenderer.php");
require_once("Response/Render/HTMLRenderer.php");
require_once("Response/Render/JSONRenderer.php");

use Helpers\DatabaseHelper;
use Helpers\SanitizationAndValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\JSONRenderer;

$DEBUG = true;

return [
    'snippets' => function(): HTTPRenderer {
        global $DEBUG;
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $snippetName = SanitizationAndValidationHelper::sanitizeString($_POST['snippet_name'] ?? '');
                $programmingLanguage = SanitizationAndValidationHelper::sanitizeString($_POST['programming_language'] ?? '');
                $validityPeriod = SanitizationAndValidationHelper::sanitizeString($_POST['validity_period'] ?? '');
                // $snippetContent = SanitizationAndValidationHelper::sanitizeText($_POST['snippet_content'] ?? '');
                $snippetContent = filter_var($_POST['snippet_content'] ?? '', FILTER_UNSAFE_RAW);


                SanitizationAndValidationHelper::validateString($snippetName);
                SanitizationAndValidationHelper::validateString($programmingLanguage);
                SanitizationAndValidationHelper::validateString($validityPeriod);
                SanitizationAndValidationHelper::validateText($snippetContent);

                if (empty($snippetName) || empty($programmingLanguage) || empty($validityPeriod) || empty($snippetContent)) {
                    throw new Exception('Missing required fields');
                }

                // 保存処理
                $uniqueString = DatabaseHelper::saveSnippet([
                    'snippet_name' => $snippetName,
                    'snippet' => $snippetContent,
                    'validity_period' => $validityPeriod,
                    'programming_language' => $programmingLanguage,
                    // 'unique_string' => $uniqueString,
                ]);

                error_log("Redirecting to: /snippets/" . $uniqueString);

                // 単票画面へのリダイレクト処理
                header('Location: /snippets/' . $uniqueString);
                exit;
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $page = SanitizationAndValidationHelper::validateInteger($_GET['page'] ?? 1);
                $perPage = SanitizationAndValidationHelper::validateInteger($_GET['perpage'] ?? 10);

                $snippets = DatabaseHelper::getSnippets($page, $perPage);
                $totalSnippets = DatabaseHelper::getTotalSnippetCount();
                $totalPages = ceil($totalSnippets / $perPage);

                // 一覧表示時にスニペットの有効期限をチェックする
                foreach ($snippets as &$snippet) {
                    DatabaseHelper::checkAndUpdateSnippetExpiration($snippet);
                }

                return new HTMLRenderer('component/snippets-list', [
                    'page' => $page,
                    'perPage' => $perPage,
                    'snippets' => $snippets,
                    'totalPages' => $totalPages,
                ]);         
            } else {
                throw new Exception('Invalid request method');
            }
        } catch (Exception $e) {
            http_response_code(500);
            if ($DEBUG) {
                echo 'Error: ' . $e->getMessage();
                echo 'Stack Trace: ' . $e->getTraceAsString();
            }
            return new JSONRenderer(['message' => $e->getMessage()]);
        }
    },
    'snippets/([^/]+)' => function($matches): HTTPRenderer {
        global $DEBUG;
        try {
            // $uniqueString = $matches[1];
            $uniqueString = SanitizationAndValidationHelper::sanitizeString($matches[1]);
            SanitizationAndValidationHelper::validateString($uniqueString);

            $snippet = DatabaseHelper::getSnippetByUniqueString($uniqueString);
            return new HTMLRenderer('component/snippet-detail', ['snippet' => $snippet]);
        } catch (Exception $e) {
            http_response_code(500);
            if ($DEBUG) {
                echo 'Error: ' . $e->getMessage();
                echo 'Stack Trace: ' . $e->getTraceAsString();
            }
            return new JSONRenderer(['message' => $e->getMessage()]);
        }
    },
    'snippet-upload' => function(): HTTPRenderer {
        return new HTMLRenderer('component/snippet-upload', []);
    },
];
