<?php
namespace App\Helpers;

/**
 * UtilityHelper
 */
class UtilityHelper
{
    /**
     * @var array
     */
    public static $exceptKey = [];
    
    /**
     * sanitizeData
     *
     * @param  mixed $data
     * @return void
     */
    public static function sanitizeData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key]  = self::sanitizeData($value);
                } else {
                    $value = trim($value);
                    $data[$key] = in_array($key, self::$exceptKey) ? $value : filter_var($value, FILTER_SANITIZE_STRING);
                }
            }
        } else {
            $data = trim(filter_var($data, FILTER_SANITIZE_STRING));
        }
        return $data;
    }
    
    /**
     * generateToken
     *
     * @return void
     */
    public static function generateToken()
    {
        return bin2hex(random_bytes(16));
    }
    
    /**
     * redirect
     *
     * @param  mixed $url
     * @return void
     */
    public static function redirect($url)
    {
        header("Location: $url");
        die();
    }
}
