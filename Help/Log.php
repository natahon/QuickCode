<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-19 10:55:55 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-19 11:22:18
 */
 namespace Help;
 define('LOGGING_ERROR', 'error');
 define('LOGGING_TRACE', 'trace');
 define('LOGGING_WARNING', 'warning');
 define('LOGGING_INFO', 'info');
 class Log{
    
    private $logFormat;

    public function __construct(){
        $this->logFormat = "%date %type %url %context %other";
    }
    
    public function _log_Running($data,$path,$type='record',$prex='data',$sys=1,$other=array()){
        if($sys != 1){
            $filename = __DIR__ . '/data/logs/' . $path . '_' . date('Ymd') . '.log';
        }else{
            $filename = 'tmp/log/'.$prex.'/'. $path . '_' . date('Ymd') . '.log'; 
        }
        $this->_mkdir($filename);
        if (is_array($data)) {
            $context[] = logging_implode($data);
        } else {
            $context[] = preg_replace('/[ \t\r\n]+/', ' ', $data);
        }
        $log = str_replace(explode(' ', $this->logFormat), array(
            '[' . date('Y-m-d H:i:s', time()) . ']',
            $type,
            $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"],
            implode("\n", $context),
            json_encode($other)
        ), $this->logFormat);
    
        file_put_contents($filename, $log . "\r\n", FILE_APPEND);
        return true;
    }

    public function _mkdir($path){
        if (!is_dir($path)) {
            mkdirs(dirname($path));
            mkdir($path);
        }
        return is_dir($path); 
    }

    public function _logging_implode($array,$skip = array()){
        $return = '';
        if (is_array($array) && !empty($array)) {
            foreach ($array as $key => $value) {
                if (empty($skip) || !in_array($key, $skip, true)) {
                    if (is_array($value)) {
                        $return .= $key . '={' . logging_implode($value, $skip) . '}; ';
                    } else {
                        $return .= "$key=$value; ";
                    }
                }
            }
        }
        return $return;
    }
 }
