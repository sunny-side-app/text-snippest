<?php

// 静的ファイルのリクエストはPHPスクリプトで処理せず、PHPビルトインサーバーのデフォルト動作に任せる
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

// index はアプリケーションのエントリーポイント。初期設定を行った後、適切なルートコールバックを呼び出して Renderer を取得し、データをレンダリングして HTTP レスポンスとして返す作業を行う。
spl_autoload_extensions(".php");
spl_autoload_register();

$DEBUG = true;
header("Access-Control-Allow-Origin: *");

// ルートを読み込みます。
//$routes = include('Routing/routes.php');
$routes = include(__DIR__ . '/../Routing/routes.php');

// リクエストURIを解析してパスだけを取得します。
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

// 静的ルートにパスが存在するかチェックする
if (isset($routes[$path])) {
    // コールバックを呼び出してrendererを作成します。
    $renderer = $routes[$path]();

    try {
        // ヘッダーを設定します。Ex) renderer: HTMLRenderer
        foreach ($renderer->getFields() as $name => $value) {
            // ヘッダーに対する単純な検証を実行します。
            $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                // ヘッダー設定に失敗した場合、ログに記録するか処理します。
                // エラー処理によっては、例外をスローするか、デフォルトのまま続行することもできます。
                http_response_code(500);
                if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
                exit;
            }
        }

        print($renderer->getContent());
    } catch (Exception $e) {
        http_response_code(500);
        print("Internal error, please contact the admin.<br>");
        if ($DEBUG) print($e->getMessage());
    }
} else {
    // 正規表現で動的ルートをチェックする
    $matched = false;
    foreach ($routes as $route => $callback) {
        $pattern = '#^' . $route . '$#';
        if (preg_match($pattern, $path, $matches)) {
            // コールバックを呼び出してrendererを作成します。
            $renderer = $callback($matches);

            try {
                // ヘッダーを設定します。Ex) renderer: HTMLRenderer
                foreach ($renderer->getFields() as $name => $value) {
                    // ヘッダーに対する単純な検証を実行します。
                    $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

                    if ($sanitized_value && $sanitized_value === $value) {
                        header("{$name}: {$sanitized_value}");
                    } else {
                        // ヘッダー設定に失敗した場合、ログに記録するか処理します。
                        // エラー処理によっては、例外をスローするか、デフォルトのまま続行することもできます。
                        http_response_code(500);
                        if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
                        exit;
                    }
                }

                print($renderer->getContent());
                $matched = true;
                break;
            } catch (Exception $e) {
                http_response_code(500);
                print("Internal error, please contact the admin.<br>");
                if ($DEBUG) print($e->getMessage());
                $matched = true;
                break;
            }
        }
    }

    if (!$matched) {
        // マッチするルートがない場合、404エラーを表示します。
        http_response_code(404);
        echo "404 Not Found: The requested route was not found on this server.";
    }
}
