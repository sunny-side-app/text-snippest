<?php

//require_once("Helpers/DatabaseHelper.php");
//require_once __DIR__ . '/../Helpers/DatabaseHelper.php';
//require_once __DIR__ . '/../Helpers/SanitizationAndValidationHelper.php';
//require_once __DIR__ . '/../Response/HTTPRenderer.php';
//require_once __DIR__ . '/../Response/Render/HTMLRenderer.php';
//require_once __DIR__ . '/../Response/Render/JSONRenderer.php';
require_once '/home/ubuntu/text-snippest/Helpers/DatabaseHelper.php';
require_once '/home/ubuntu/text-snippest/Helpers/SanitizationAndValidationHelper.php';
require_once '/home/ubuntu/text-snippest/Response/HTTPRenderer.php';
require_once '/home/ubuntu/text-snippest/Response/Render/HTMLRenderer.php';
require_once '/home/ubuntu/text-snippest/Response/Render/JSONRenderer.php';

use Helpers\DatabaseHelper;
use Helpers\SanitizationAndValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\JSONRenderer;

$DEBUG = true;
error_log("Inside 'snippets' route callback");

return [
    'snippets' => function(): HTTPRenderer {
        global $DEBUG;
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		error_log("Handling POST request");
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
		error_log("Handling GET request");
		error_log("Page before validation: $page");
                $page = SanitizationAndValidationHelper::validateInteger($_GET['page'] ?? 1);
                $perPage = SanitizationAndValidationHelper::validateInteger($_GET['perpage'] ?? 10);

		error_log("Page: $page, PerPage: $perPage");

                $snippets = DatabaseHelper::getSnippets($page, $perPage);
		error_log("Snippets retrieved: " . count($snippets));
                $totalSnippets = DatabaseHelper::getTotalSnippetCount();
                $totalPages = ceil($totalSnippets / $perPage);
		error_log("Total snippets: $totalSnippets, Total pages: $totalPages");

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
		error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
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
	    error_log("Handling snippets detail request with matches: " . print_r($matches, true)); // 追加
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
	error_log("Handling snippet upload request"); // 追加
        return new HTMLRenderer('component/snippet-upload', []);
    },
];
