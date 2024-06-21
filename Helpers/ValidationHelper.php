<?php

namespace Helpers;

class ValidationHelper
{
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        // デフォルト値の設定
        if ($value === null) {
            $value = 1; // または他の適切なデフォルト値
        }
        // デバッグ情報の追加
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("The provided value is not a valid integer. Value: " . var_export($value, true));
        }
        // データを検証する組み込み関数https://www.php.net/manual/ja/function.filter-var.php
        // array is option: https://www.php.net/manual/ja/filter.filters.validate.php
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range"=>(int) $max]);

        // 結果がfalseの場合、フィルターは失敗したことになります。
        if ($value === false) {
            throw new \InvalidArgumentException("The provided value is not a valid integer. Value: " . var_export($value, true));
        }
    
        // 値がすべてのチェックをパスしたら、そのまま返します。
        return $value;
    }
}