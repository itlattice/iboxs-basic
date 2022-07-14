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

    /**
     * 简单发起post请求(更多请求方式或请求需要可安装：composer require iboxs/http)
     * @param $url
     * @param array $post_data
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
}