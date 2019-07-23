<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-22 17:40:49 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-23 13:58:29
 */
define('ROOT',__DIR__);
define('CORE',ROOT.'/Core');
define('OTH',ROOT.'/Other');
define('PUB',ROOT.'/Public');
define('IMG',PUB.'/image');
define('HTML',PUB.'/template');
define('TEST',ROOT.'/Test');

// error_reporting(0);
date_default_timezone_set('PRC');

$array = array(
    'error' => TEST.'/Error.php',
);

foreach ($array as $k => $v) {
    require $v;
}