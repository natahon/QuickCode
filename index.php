<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-22 13:59:29 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-23 18:04:02
 */
class Loader{
     
    public static $vendorMap = array(
        'Core' => __DIR__ . DIRECTORY_SEPARATOR.'Core',
        'Help' => __DIR__ . DIRECTORY_SEPARATOR.'Help',
        'Pub' => __DIR__ . DIRECTORY_SEPARATOR.'Pub',
        'Other' => __DIR__ . DIRECTORY_SEPARATOR.'Other',
    );

    public static function autoload($class){
        $file = self::_findfiles($class);
        if (file_exists($file)) {
            self::_includeFile($file);
        }
    }

    private static function _findfiles($class){
        $vendor = substr($class, 0, strpos($class, '\\')); 
        $vendorDir = self::$vendorMap[$vendor];
        $filePath = substr($class, strlen($vendor)) . '.php'; 
        return strtr($vendorDir . $filePath, '\\', DIRECTORY_SEPARATOR); 
    }

    private static function _includeFile($file){
        if (is_file($file)) {include $file;}
    }
}
require_once "config.php";
spl_autoload_register('Loader::autoload');
set_error_handler('getErrorMsg');
// $a = new Core\Wechat\AccessToken();
// $appid = 'wxd67180f31553c983_1';
// $secret = '3d0aea53cf0032076150e4d0009517b6';
// $a->_Assignment($appid,$secret);
// var_dump($a->_getAccessToken());

use Other\a\c\ApiDoc as doc;

$api = new doc('./Core/Wechat'); 

$api->setName('Doc');

$api->make();


