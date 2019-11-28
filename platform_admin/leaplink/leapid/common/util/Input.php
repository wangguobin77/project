<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/9/25
 * Time: 下午3:29
 */

namespace App\util;


class input{

    /**
     * Returns an array with all the variables in the GET header, fetching them
     * @static
     */
    public static function get($key = '', $value = '')
    {
        if ($value !== '') $_GET[$key] = $value;
        if ($key) return @$_GET[$key];
        return $_GET;
    }

    /**
     * Returns an array with all the variables in the POST header, fetching them
     */
    public static function post($key = '', $value = '')
    {
        if ($value !== '') $_POST[$key] = $value;
        if ($key) return @$_POST[$key];
        return $_POST;
    }


    /**
     * Returns an array with all the variables in the session, fetching them
     */
    public static function session($key = '', $value = '')
    {
        if ($value !== '') $_SESSION[$key] = $value;
        if ($key) return @$_SESSION[$key];
        return $_SESSION;
    }

    /**
     * Returns an array with the contents of the $_COOKIE global variable
     */
    public static function cookie($key = '', $value = '', $time = 3600)
    {
        if ($value) {
            $_COOKIE[$key] = $value;
            setcookie($key, $value, time() + $time, '/');
        }elseif ($value === null){
            setcookie($key, $value, time() - 24*60*60, '/');
            return ;
        }
        if ($key) return @$_COOKIE[$key];
        return $_COOKIE;
    }

    /**
     * Returns the value of the $_REQUEST array. In PHP >= 4.1.0 it is defined as a mix
     * of the $_POST, $_GET and $_COOKIE arrays, but it didn't exist in earlier versions.
     */
    public static function request($key = '', $value = '')
    {
        if ($value !== '') $_REQUEST[$key] = $value;
        if ($key) return @$_REQUEST[$key];
        return $_REQUEST;
    }

    /**
     * Returns the $_SERVER array, otherwise known as $HTTP_SERVER_VARS in versions older
     * than PHP 4.1.0
     */
    public static function server($key = '', $value = '')
    {
        if ($value !== '') $_SERVER[$key] = $value;
        if ($key) return @$_SERVER[$key];
        return $_SERVER;
    }

    /**
     * Returns the $_SERVER array, otherwise known as $HTTP_SERVER_VARS in versions older
     * than PHP 4.1.0
     */
    public static function file($key = '')
    {
        if ($key) return @$_FILES[$key];
        return $_FILES;
    }


    /**
     * Returns the base URLs of the script
     * base/path/request/query/self
     */
    public static function uri($key = '', $value = false, $param = false)
    {
        if ($value){
            $c = substr($value, 0, 1);
            if ('/' === $c) return 'http://'.self::server('HTTP_HOST').$value;
            $pos = strrpos(self::server('PATH_INFO'), '/');
            $url = substr(self::server('PATH_INFO'), 0, $pos+1).$value;

            if (strpos($url, '?')) return $url;

            $houzui = F::config('suffix');
            $url .= $houzui;

            if($param) {
                $url .= '?'.self::server('QUERY_STRING');
            }
            return $url;
        }
        switch ($key){
            case 'base': {
                return self::server('HTTP_HOST')?'http://'.self::server('HTTP_HOST').'/':F::config('domain');
            }
            case 'path': return self::server('PATH_INFO');
            case 'request': return self::server('REQUEST_URI');
            case 'query': return self::server('QUERY_STRING');
            case 'self': return self::server('PHP_SELF');
            default:	return '';
        }
    }

}