<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-22 17:40:49 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-22 17:56:32
 */
define('ROOT',__DIR__);
define('CORE',ROOT.'/Core');
define('OTH',ROOT.'/Other');
define('PUB',ROOT.'/Public');
define('IMG',PUB.'/image');
define('HTML',PUB.'/template');
define('TEST',ROOT.'/Test');

// error_reporting(0);

$array = array(
    'error' => TEST.'/Error.php',
);

foreach ($array as $k => $v) {
    require $v;
}