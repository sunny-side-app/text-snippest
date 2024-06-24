<?php

namespace Helpers;

class SanitizationAndValidationHelper
{
    // サニタイズメソッド
    public static function sanitizeString($string) {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    public static function sanitizeText($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    // 検証メソッド
    public static function validateString($string) {
        // 許可する文字セットを拡張（例：英数字、アンダースコア、ハイフン、スペース、コロン、カッコ、ピリオド）
        if (preg_match('/[^A-Za-z0-9_\- :().]/', $string)) {
            throw new \Exception('Unsupported characters detected in the string');
        }
        return $string;
    }
    
    public static function validateText($text) {
        // 特殊文字のセットを拡張
        $allowedCharacters = '/[^\x20-\x7E\n\r\t{};()<>\[\]\\\/\'\".:,\+\-\*\?\!]/';
        if (preg_match($allowedCharacters, $text)) {
            throw new \Exception('Unsupported characters detected in the text');
        }
        return $text;
    }
    

    // 整数検証メソッド
    public static function validateInteger($value, float $min = -INF, float $max = INF): int
    {
        // デフォルト値の設定
        if ($value === null) {
            $value = 1; // または他の適切なデフォルト値
        }
        // デバッグ情報の追加
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("The provided value is not a valid integer. Value: " . var_export($value, true));
        }
        // データを検証する組み込み関数 https://www.php.net/manual/ja/function.filter-var.php
        // array is option: https://www.php.net/manual/ja/filter.filters.validate.php
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range" => (int) $max]);

        // 結果がfalseの場合、フィルターは失敗したことになります。
        if ($value === false) {
            throw new \InvalidArgumentException("The provided value is not a valid integer. Value: " . var_export($value, true));
        }

        // 値がすべてのチェックをパスしたら、そのまま返します。
        return $value;
    }
}