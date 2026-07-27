<?php 
namespace Framework;

class Validation
{
    public static function string(string $value, int $min = 1, int $max = INF): bool {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);
            return $length >= $min && $length <= $max;
        } else {
            return false;
        }
    }

    public static function email(string $value): mixed {
        $value = trim($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    
    public static function match($value1, $value2): bool {
        $value1 = trim($value1);
        $value2 = trim($value2);

        return $value1 === $value2;
    }
    
}