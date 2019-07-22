<?php
/*
 * @Author: YingBinXia 
 * @Date: 2019-07-19 11:40:12 
 * @Last Modified by: YingBinXia
 * @Last Modified time: 2019-07-19 11:48:55
 */
namespace Help;

class Request{
    
    public static function _HttpRequest($url, $post = '', $extra = array(), $timeout = 60){
        if (function_exists('curl_init') && function_exists('curl_exec') && $timeout > 0) {
            $ch = ihttp_build_curl($url, $post, $extra, $timeout);
            if (is_error($ch)) {
                return $ch;
            }
            $data = curl_exec($ch);
            $status = curl_getinfo($ch);
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);
            if ($errno || empty($data)) {
                return error($errno, $error);
            } else {
                return $this->ihttp_response_parse($data);
            }
        }
        $urlset = $this->ihttp_parse_url($url, true);
        if (!empty($urlset['ip'])) {
            $urlset['host'] = $urlset['ip'];
        }
    
        $body = $this->ihttp_build_httpbody($url, $post, $extra);
    
        if ($urlset['scheme'] == 'https') {
            $fp = $this->ihttp_socketopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
        } else {
            $fp = $this->ihttp_socketopen($urlset['host'], $urlset['port'], $errno, $error);
        }
        stream_set_blocking($fp, $timeout > 0 ? true : false);
        stream_set_timeout($fp, ini_get('default_socket_timeout'));
        if (!$fp) {
            return error(1, $error);
        } else {
            fwrite($fp, $body);
            $content = '';
            if($timeout > 0) {
                while (!feof($fp)) {
                    $content .= fgets($fp, 512);
                }
            }
            fclose($fp);
            return $this->ihttp_response_parse($content, true);
        }
    }

    private function ihttp_response_parse(){
        function ihttp_response_parse($data, $chunked = false) {
            $rlt = array();
        
            $headermeta = explode('HTTP/', $data);
            if (count($headermeta) > 2) {
                $data = 'HTTP/' . array_pop($headermeta);
            }
            $pos = strpos($data, "\r\n\r\n");
            $split1[0] = substr($data, 0, $pos);
            $split1[1] = substr($data, $pos + 4, strlen($data));
        
            $split2 = explode("\r\n", $split1[0], 2);
            preg_match('/^(\S+) (\S+) (.*)$/', $split2[0], $matches);
            $rlt['code'] = !empty($matches[2]) ? $matches[2] : 200;
            $rlt['status'] = !empty($matches[3]) ? $matches[3] : 'OK';
            $rlt['responseline'] = !empty($split2[0]) ? $split2[0] : '';
            $header = explode("\r\n", $split2[1]);
            $isgzip = false;
            $ischunk = false;
            foreach ($header as $v) {
                $pos = strpos($v, ':');
                $key = substr($v, 0, $pos);
                $value = trim(substr($v, $pos + 1));
                if (is_array($rlt['headers'][$key])) {
                    $rlt['headers'][$key][] = $value;
                } elseif (!empty($rlt['headers'][$key])) {
                    $temp = $rlt['headers'][$key];
                    unset($rlt['headers'][$key]);
                    $rlt['headers'][$key][] = $temp;
                    $rlt['headers'][$key][] = $value;
                } else {
                    $rlt['headers'][$key] = $value;
                }
                if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
                    $isgzip = true;
                }
                if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
                    $ischunk = true;
                }
            }
            if($chunked && $ischunk) {
                $rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
            } else {
                $rlt['content'] = $split1[1];
            }
            if($isgzip && function_exists('gzdecode')) {
                $rlt['content'] = gzdecode($rlt['content']);
            }
        
            $rlt['meta'] = $data;
            if($rlt['code'] == '100') {
                return ihttp_response_parse($rlt['content']);
            }
            return $rlt;
        }
    }

    private function ihttp_parse_url($url, $set_default_port = false) {
        if (empty($url)) {
            return error(1);
        }
        $urlset = parse_url($url);
        if (!empty($urlset['scheme']) && !in_array($urlset['scheme'], array('http', 'https'))) {
            return error(1, '只能使用 http 及 https 协议');
        }
        if (empty($urlset['path'])) {
            $urlset['path'] = '/';
        }
        if (!empty($urlset['query'])) {
            $urlset['query'] = "?{$urlset['query']}";
        }
        if (strexists($url, 'https://') && !extension_loaded('openssl')) {
            if (!extension_loaded("openssl")) {
                return error(1,'请开启您PHP环境的openssl', '');
            }
        }
        if (empty($urlset['host'])) {
            $current_url = parse_url($GLOBALS['_W']['siteroot']);
            $urlset['host'] = $current_url['host'];
            $urlset['scheme'] = $current_url['scheme'];
            $urlset['path'] = $current_url['path'] . 'web/' . str_replace('./', '', $urlset['path']);
            $urlset['ip'] = '127.0.0.1';
        } else if (! ihttp_allow_host($urlset['host'])){
            return error(1, 'host 非法');
        }
    
        if ($set_default_port && empty($urlset['port'])) {
            $urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
        }
        return $urlset;
    }

    private function ihttp_build_httpbody($url, $post, $extra) {
        $urlset = ihttp_parse_url($url, true);
        if (is_error($urlset)) {
            return $urlset;
        }
    
        if (!empty($urlset['ip'])) {
            $extra['ip'] = $urlset['ip'];
        }
    
        $body = '';
        if (!empty($post) && is_array($post)) {
            $filepost = false;
            $boundary = random(40);
            foreach ($post as $name => &$value) {
                if ((is_string($value) && substr($value, 0, 1) == '@') && file_exists(ltrim($value, '@'))) {
                    $filepost = true;
                    $file = ltrim($value, '@');
    
                    $body .= "--$boundary\r\n";
                    $body .= 'Content-Disposition: form-data; name="'.$name.'"; filename="'.basename($file).'"; Content-Type: application/octet-stream'."\r\n\r\n";
                    $body .= file_get_contents($file)."\r\n";
                } else {
                    $body .= "--$boundary\r\n";
                    $body .= 'Content-Disposition: form-data; name="'.$name.'"'."\r\n\r\n";
                    $body .= $value."\r\n";
                }
            }
            if (!$filepost) {
                $body = http_build_query($post, '', '&');
            } else {
                $body .= "--$boundary\r\n";
            }
        }
    
        $method = empty($post) ? 'GET' : 'POST';
        $fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
        $fdata .= "Accept: */*\r\n";
        $fdata .= "Accept-Language: zh-cn\r\n";
        if ($method == 'POST') {
            $fdata .= empty($filepost) ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data; boundary=$boundary\r\n";
        }
        $fdata .= "Host: {$urlset['host']}\r\n";
        $fdata .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1\r\n";
        if (function_exists('gzdecode')) {
            $fdata .= "Accept-Encoding: gzip, deflate\r\n";
        }
        $fdata .= "Connection: close\r\n";
        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $opt => $value) {
                if (!strexists($opt, 'CURLOPT_')) {
                    $fdata .= "{$opt}: {$value}\r\n";
                }
            }
        }
        if ($body) {
            $fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
        } else {
            $fdata .= "\r\n";
        }
        return $fdata;
    }

    private function ihttp_socketopen($hostname, $port = 80, &$errno, &$errstr, $timeout = 15) {
        $fp = '';
        if(function_exists('fsockopen')) {
            $fp = @fsockopen($hostname, $port, $errno, $errstr, $timeout);
        } elseif(function_exists('pfsockopen')) {
            $fp = @pfsockopen($hostname, $port, $errno, $errstr, $timeout);
        } elseif(function_exists('stream_socket_client')) {
            $fp = @stream_socket_client($hostname.':'.$port, $errno, $errstr, $timeout);
        }
        return $fp;
    }
}