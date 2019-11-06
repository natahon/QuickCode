<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-24 10:23:55 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-24 17:41:34
 */
namespace SYS;
class Sql{
    public $hash;
    private $connect;
    static protected $ins=null;
    final protected function __construct(){
        $this->hash=rand(1,9999);
    }
    private function __clone(){}
    public static function getInstance(){
        if (self::$ins instanceof self) {
            return self::$ins;
        }
        self::$ins=new self();
        return self::$ins;
    }
    public function connect($host, $user, $pass, $dbname){
        $this->connect = mysqli_connect($host,$user,$pass,$dbname);
    }
    public function query($sql){
        var_dump($this->connect);
        return mysqli_query($this->connect,$sql);
    }
    public function close(){
        return mysqli_close($this->connect);
    }
}