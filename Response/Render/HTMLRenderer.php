<?php

// HTMLRenderer.php: HTML ページ用の Renderer で、サーバサイドレンダリングを簡単にセットアップして管理できます。ビューファイルのパスと、オプションでデータアイテムのハッシュマップを受け取り、各キーがビューで使用する変数に変換されます。

namespace Response\Render;

require_once("Response/HTTPRenderer.php");
use Response\HTTPRenderer;

class HTMLRenderer implements HTTPRenderer
{
    private string $viewFile;
    private array $data;

    public function __construct(string $viewFile, array $data = []) {
        $this->viewFile = $viewFile;
        $this->data = $data;
    }

    public function getFields(): array {
        return [
            'Content-Type' => 'text/html; charset=UTF-8',
        ];
    }

    public function getContent(): string {
        $viewPath = $this->getViewPath($this->viewFile);

        if (!file_exists($viewPath)) {
            throw new \Exception("View file {$viewPath} does not exist.");
        }

        // ob_startはすべての出力をバッファに取り込みます。
        // このバッファはob_get_cleanによって取得することができ、バッファの内容を返し、バッファをクリアします。
        ob_start();
        // extract関数は、連想配列の各キーを変数として対応する各値をそれに代入する
        extract($this->data);
        require $viewPath;
        $content = ob_get_clean();
        return $this->getHeader() . $content . $this->getFooter();
    }

    private function getHeader(): string{
        ob_start();
        require $this->getViewPath('layout/header');
        return ob_get_clean();
    }

    private function getFooter(): string{
        ob_start();
        require $this->getViewPath('layout/footer');
        return ob_get_clean();
    }

    private function getViewPath(string $path): string{
        return sprintf("%s/%s/Views/%s.php",__DIR__, '../..',$path);
    }
}