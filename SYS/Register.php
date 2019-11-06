<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-24 15:11:01 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-24 15:11:24
 */
namespace SYS;
class Register{
    protected static $objects;
    public static function set($alias,$object){
      self::$objects[$alias]=$object;
    }
    public static function get($alias){
      return self::$objects[$alias];
    }
    public static function _unset($alias){
      unset(self::$objects[$alias]);
    }
}