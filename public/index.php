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
//error_log("Request path: {$path}");
error_log("Request path: '{$path}'"); // 追加: パスが空でないか確認するためにシングルクォートで囲む
// ルート設定のデバッグ情報を追加
error_log("Routes configuration: " . print_r(array_keys($routes), true));



// 静的ルートにパスが存在するかチェックする
if (isset($routes[$path])) {
    error_log("Found static route for path: {$path}");
    // コールバックを呼び出してrendererを作成します。
    $renderer = $routes[$path]();

    try {
        // ヘッダーを設定します。Ex) renderer: HTMLRenderer
        foreach ($renderer->getFields() as $name => $value) {
            // ヘッダーに対する単純な検証を実行します。
            $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	    error_log("Setting header - original: '$value', sanitized: '$sanitized_value'");

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                // ヘッダー設定に失敗した場合、ログに記録するか処理します。
                // エラー処理によっては、例外をスローするか、デフォルトのまま続行することもできます。
		error_log("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
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
	error_log("Error: " . $e->getMessage());
	error_log("Stack Trace: " . $e->getTraceAsString());
    }
    error_log("Renderer created for static route");
} else {
    error_log("Checking dynamic routes for path: '{$path}'"); // 追加
    // 正規表現で動的ルートをチェックする
    $matched = false;
    foreach ($routes as $route => $callback) {
        $pattern = '#^' . $route . '$#';
        if (preg_match($pattern, $path, $matches)) {
            // コールバックを呼び出してrendererを作成します。
	    error_log("Matched dynamic route for path: {$path}");
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
	error_log("Renderer created for dynamic route");
        }
    }

    if (!$matched) {
        // マッチするルートがない場合、404エラーを表示します。
        http_response_code(404);
        echo "404 Not Found: The requested route was not found on this server.";
    }
}
