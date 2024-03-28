<?php

namespace iboxs\basic\traits;

trait Http
{

    /**
     * 获取顶级域名
     * @param $url 网址信息
     * @return string
     */
   public function getTopHost($url): string
   {
        $url = strtolower($url);  //首先转成小写
        $hosts = parse_url($url);
        $host = $hosts['host'];
        //查看是几级域名
        $data = explode('.', $host);
        $n = count($data);
        //判断是否是双后缀
        $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        if (($n > 2) && preg_match($preg, $host)) {
            //双后缀取后3位
            $host = $data[$n - 3] . '.' . $data[$n - 2] . '.' . $data[$n - 1];
        } else {
            //非双后缀取后两位
            $host = $data[$n - 2] . '.' . $data[$n - 1];
        }
        return $host;
    }

    public function sendJson($url,$data,&$httpCode=200){
        $jsonStr=json_encode($data,JSON_UNESCAPED_UNICODE);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr)
            )
        );
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }

    /**
     * 简单发起post请求(更多请求方式或请求需要可安装：composer require iboxs/http)
     * @param $url
     * @param array $post_data 键值对形式
     * @return false|string
     */
   public function send_post($url, $post_data)
    {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public function curl_post_send($url, $params, $header){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $return_content = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($httpcode, $return_content);
    }

    public function sendJson($url,$json,$header){
//       dd($url,$json);
        $httph = curl_init($url);
        curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        $headers = array(
            'Content-Type: application/json;charset=UTF-8'
        );
        $headers=array_merge($headers,$header);
        curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($httph, CURLOPT_POSTFIELDS, $json);
        curl_setopt($httph, CURLOPT_CONNECTTIMEOUT, 3);//设置超时时间
        curl_setopt($httph, CURLOPT_HTTPHEADER, $headers);
        $rst = curl_exec($httph);
        $httpCode = curl_getinfo($httph, CURLINFO_HTTP_CODE);
        $data = json_decode($rst,true);
        curl_close($httph);
        return $data;
    }

    /**
     * 判断请求是否来自微信
     * @return bool
     */
    public function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }


    /**
     * 获得访问者浏览器语言
     */
   public function get_lang($agent)
    {
        if (!empty($agent)) {
            $lang = strtolower($agent);
            $lang = substr($lang, 0, 5);
            if (preg_match('/zh-cn/i', $lang)) {
                $lang = '简体中文';
            } else if (preg_match('/zh/i', $lang)) {
                $lang = '繁体中文';
            } else {
                $lang = 'English';
            }
            return $lang;
        } else {
            return 'unknow';
        }
    }


    /**
     * 获得访客操作系统
     */
   public function get_os($agent)
    {
        $agent = strtolower($agent);
        if (strpos($agent, 'windows nt')) {
            $platform = 'windows';
        } elseif (strpos($agent, 'macintosh')) {
            $platform = 'mac';
        } elseif (strpos($agent, 'ipod')) {
            $platform = 'ipod';
        } elseif (strpos($agent, 'ipad')) {
            $platform = 'ipad';
        } elseif (strpos($agent, 'iphone')) {
            $platform = 'iphone';
        } elseif (strpos($agent, 'android')) {
            $platform = 'android';
        } elseif (strpos($agent, 'unix')) {
            $platform = 'unix';
        } elseif (strpos($agent, 'linux')) {
            $platform = 'linux';
        } else {
            $platform = 'other';
        }
        return $platform;
    }

    /**
     * 获取浏览器agent
     * @return mixed|string
     */
    public function GetBrowser()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    /**
     * 获得访问者浏览器
     */
    function browse_info($agent)
    {
        if (!empty($agent)) {
            $br = $agent;
            if (preg_match('/MSIE/i', $br)) {
                return 'MSIE';
            } else if (preg_match('/Firefox/i', $br)) {
                return 'Firefox';
            } else if (preg_match('/Chrome/i', $br)) {
                return 'Chrome';
            } else if (preg_match('/Safari/i', $br)) {
                return 'Safari';
            } else if (preg_match('/Opera/i', $br)) {
                return 'Opera';
            } else {
                return 'Other';
            }
            return $br;
        } else {
            return 'unknow';
        }
    }
    
    public function sendGetHeader($url,$headers){
        $ch = curl_init();
        # 判断是否是https
        if (stripos($url, "https://") !== false) {
            # 禁用后cURL将终止从服务端进行验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            # 	使用的SSL版本(2 或 3)
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        # 设置请求地址
        curl_setopt($ch, CURLOPT_URL, $url);
        # 	在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
        # 执行这个请求
        $output = curl_exec($ch);
        # 关闭这个请求
        curl_close($ch);
        return $output;
    }

    public function GetPage($url){
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36\r\n".
                            "X-Requested-With:XMLHttpRequest\r\n"
            )
        ));
        // 发送GET请求
        $response = file_get_contents($url, false, $context);
        return $response;
    }
}
