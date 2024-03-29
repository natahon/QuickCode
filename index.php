<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-22 13:59:29 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-24 17:39:38
 */
class Loader{
     
    public static $vendorMap = array(
        'Core' => __DIR__ . DIRECTORY_SEPARATOR.'Core',
        'Help' => __DIR__ . DIRECTORY_SEPARATOR.'Help',
        'Pub' => __DIR__ . DIRECTORY_SEPARATOR.'Pub',
        'Other' => __DIR__ . DIRECTORY_SEPARATOR.'Other',
        'Common' => __DIR__ . DIRECTORY_SEPARATOR.'Common',
        'SYS' => __DIR__ . DIRECTORY_SEPARATOR.'SYS',
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
// $appid = 'wxd67180f31553c983';
// $secret = '3d0aea53cf0032076150e4d0009517b6';
// $a->_Assignment($appid,$secret);
// var_dump($a->_getAccessToken());

$a = new Core\Wechat\Material();
$result = $a->batchget_material();
echo $result;






















// use Other\a\c\ApiDoc as doc;
// $api = new doc('./Core/Wechat'); 
// $api->setName('Doc');
// $api->make();

//use Common\Mysql as sql;
//use SYS\Factory as fac;
//$sql = new sql();
//
//
//fac::factory()->connect('127.0.0.1','root','root','test');
//
//$result = $sql->table('ims_wechat_activity_children')->where('id=1')->delete();
//
//var_dump($result);

