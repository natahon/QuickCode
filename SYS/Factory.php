<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-24 15:10:12 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-24 16:47:42
 */
namespace SYS;
use SYS\Sql as Sql;
class Factory{
    public static function factory(){
        return Sql::getInstance();
    }
}
