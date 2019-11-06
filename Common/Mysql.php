<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-24 11:31:09 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-24 16:29:10
 */
namespace Common;
use SYS\Factory as fac;
class Mysql{
    
    private $sql = array(
        'order' => '',
        'limit' => '',
        'where' => '',
        'having' => '',
        'field' => '',
        'group' => '',
        'table' => ''
    );
    
    public function __construct($str=null){}

    public function __call($method,$args){
        $method = strtolower($method);  
        if(array_key_exists($method,$this->sql)){  
            $this->sql[$method] = $args[0];  
        }else{  
            echo '调用类'.get_class($this).'中的方法'.$method.'()不存在';  
        }   
        return $this;
    }

    public function select(){
        $sql = "SELECT {$this->sql['field']} FROM {$this->sql['table']}
        {$this->sql['where']} {$this->sql['order']} {$this->sql['limit']} 
        {$this->sql['group']} {$this->sql['having']}";
        return $this->query($sql);
    }

    public function update($array){
        $str = '';
        foreach ($array as $k => $v) {
            $str .= $k . '=' . $v .',';
        } 
        $sql = "UPDATE {$this->sql['table']} SET ".substr($str,0,-1)." {$this->sql['where']}";
        return $this->query($sql);
    }

    public function delete(){
        $sql = "DELETE FROM {$this->sql['table']} {$this->sql['where']}";
        return $this->query($sql);
    }

    private function query($param){
        $obj = fac::factory();
        return $obj->query($param);
    }    
}