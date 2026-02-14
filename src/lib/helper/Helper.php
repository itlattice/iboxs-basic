<?php
namespace iboxs\basic\lib\helper;

use DateTime;
use DateTimeZone;

class Helper{
    /**
     * 获取域名中的顶级域名
     * @param string $url 需要解析的URL地址
     * @return string 返回解析后的顶级域名
     */
    public function getTopDomain(string $url):string{
        $url = strtolower($url);
        $hosts = parse_url($url);
        $host = $hosts['host'];
        $data = explode('.', $host);
        $n = count($data);
        $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        if (($n > 2) && preg_match($preg, $host)) {
            $host = $data[$n - 3] . '.' . $data[$n - 2] . '.' . $data[$n - 1];
        } else {
            $host = $data[$n - 2] . '.' . $data[$n - 1];
        }
        return $host;
    }

    /**
     * 判断请求是否来自微信浏览器
     * @return bool 如果请求来自微信浏览器则返回true，否则返回false
     */
    public function isWechatBrowser():bool{
        if (str_contains($_SERVER['HTTP_USER_AGENT'] ?? '', 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断请求是否来自支付宝浏览器
     * @return bool 如果请求来自支付宝浏览器则返回true，否则返回false
     */
    public function isAlipayBrowser():bool{
        if (str_contains($_SERVER['HTTP_USER_AGENT'] ?? '', 'AlipayClient') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断请求是否来自移动设备
     * @return bool 如果请求来自移动设备则返回true，否则返回false
     */
    public function isMobile():bool{
        $userAgent =$_SERVER['HTTP_USER_AGENT'] ?? '';
        $mobileDevices = [
            '/android/i',
            '/webos/i',
            '/iphone/i',
            '/ipad/i',
            '/ipod/i',
            '/blackberry/i',
            '/iemobile/i',
            '/opera mini/i',
            '/mobile/i'
        ];
    
        foreach ($mobileDevices as $device) {
            if (preg_match($device, $userAgent)) {
                return true;
            }
        }
    
        return false;
    }

    /**
     * 获取浏览器语言
     * @return string 返回浏览器语言的字符串表示
     */
    public function getBrowserLang(){
        $agent =$_SERVER['HTTP_USER_AGENT'] ?? '';
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
     * 获取访客的操作系统
     * @return string 返回访客操作系统的字符串表示
     */
    public function getOS(){
        $agent =$_SERVER['HTTP_USER_AGENT'] ?? '';
        $agent = strtolower($agent);
        if (str_contains($agent, 'windows nt')) {
            $platform = 'windows';
        } elseif (str_contains($agent, 'macintosh')) {
            $platform = 'mac';
        } elseif (str_contains($agent, 'ipod')) {
            $platform = 'ipod';
        } elseif (str_contains($agent, 'ipad')) {
            $platform = 'ipad';
        } elseif (str_contains($agent, 'iphone')) {
            $platform = 'iphone';
        } elseif (str_contains($agent, 'android')) {
            $platform = 'android';
        } elseif (str_contains($agent, 'unix')) {
            $platform = 'unix';
        } elseif (str_contains($agent, 'linux')) {
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
    public function GetUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    /**
     * 获得访问者浏览器
     */
    function GetBrowser()
    {
        $agent =$_SERVER['HTTP_USER_AGENT'] ?? '';
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
    /**
     * 对象转Array
     * @param $array
     * @return array|mixed
     */
    public function objectToArray($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] =$this-> objectToArray($value);
            }
        }
        return $array;
    }
    /**
     * 图片base64解码
     * @param string $base64_image_content 图片文件流
     * @param bool $save_img 是否保存图片
     * @param string $path 文件保存路径
     * @return bool|string
     */
    public function imgBase64Decode(string $base64_image_content = '', bool $save_img = false, string $file_path = '')
    {
        if (empty($base64_image_content)) {
            return false;
        }

        //匹配出图片的信息
        $match = preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result);
        if (!$match) {
            return false;
        }

        $base64_image = str_replace($result[1], '', $base64_image_content);
        $file_content = base64_decode($base64_image);
        $file_type = $result[2];

        //如果不保存文件,直接返回图片内容
        if (!$save_img) {
            return $file_content;
        }

        $file_name = microtime(true) . ".{$file_type}";
        $new_file = $file_path . $file_name;
        if (file_exists($new_file)) {
            //有同名文件删除
            @unlink($new_file);
        }
        if (file_put_contents($new_file, $file_content)) {
            return $new_file;
        }
        return false;
    }

    /**
     * 加锁写入文件
     * @param string $file 文件路径
     * @param string $text 字符串
     * @param string $mode 写入方式
     * @param int $timeout 最长等待时间
     * @return bool
     */
    public function fileWrite(string $file, string $text, string $mode = 'a+', int $timeout = 5): bool
    {
        $handle = fopen($file, $mode);
        while ($timeout > 0) {
            if (!is_writable($file)) {
                $timeout--;
                sleep(1);
            } else {
                flock($handle, LOCK_EX);
                fwrite($handle, $text);
                flock($handle, LOCK_UN);
                fclose($handle);
                return true;
            }
        }
        return false;
    }

    /**
     * 获取文件扩展名
     * @param string $file 文件路径
     * @return string 返回文件的扩展名
     */
    public function getFileExt(string $file): string{
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        return $ext;
    }
    /**
     * 获取服务器信息
     * @param string $key 项
     * @return array|mixed
     */
    public function getSystemInfo(string $key='')
    {
        $system = [
            'os' => PHP_OS,
            'version' => PHP_VERSION,
            'upload_max_filesize' => get_cfg_var("upload_max_filesize") ? get_cfg_var("upload_max_filesize") : 0,
            'max_execution_time' => get_cfg_var("max_execution_time")
        ];
        if (empty($key)) {
            return $system;
        } else {
            return $system[$key];
        }
    }

    /**
     * 判断字符串是否是JSON
     * @param string $str 需要判断的字符串
     * @return bool 如果字符串是JSON格式则返回true，否则返回false
     */
    public function isJson(string $str): bool{
        return (!is_null(json_decode($str)));
    }

    /**
     * 获取随机字符串
     * @param int $length 随机字符串长度
     * @return string
     */
    public function GetRandomStr(int $length = 8): string
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randStr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = \mt_rand(0, $len);
            $randStr .= $str[$num];
        }
        return $randStr;
    }

    /**
     * 删除字符串中的emoji表情
     * @param string|array $str 需要处理的字符串或字符串数组
     * @return string|array 处理后的字符串或字符串数组
     */
    public function DelEmoji(string|array $str): string|array{
        if(is_array($str)){
            foreach($str as $key=>$val){
                $str[$key] = $this->delEmoji($val);
            }
            return $str;
        }
        if(is_numeric($str)){
            return $str;
        }
        if(is_bool($str)){
            return $str;
        }
        if(is_object($str)){
            return $str;
        }
        $mbLen = mb_strlen($str); $strArr = [];
        for ($i = 0; $i < $mbLen; $i++) { $mbSubstr = mb_substr($str, $i, 1, 'utf-8');
        if (strlen($mbSubstr) >= 4) { continue; } $strArr[] = $mbSubstr; } 
        $str=implode('', $strArr);
        return mb_convert_encoding($str, "UTF-8");
    }

    /**
     * 计算两个字符串的相同长度
      * @param string $str1 第一个字符串
      * @param string $str2 第二个字符串
       * @return int 返回两个字符串的相同长度
     */
    public function sameStr(string $str1, string $str2): int{
        $len=mb_strlen($str1);
        $count=0;
        for($i=1;$i<$len+1;$i++){
            if(mb_substr($str1,0,$i)==mb_substr($str2,0,$i)){
                $count=$i;
            }
        }
        return $count;
    }

    /**
     * 处理手机号，隐藏中间4位
    * @param string $mobile 需要处理的手机号字符串
    * @return string 返回处理后的手机号字符串
     */
    public function phoneHandle(string $mobile): string{
        if(strlen($mobile)<8){
            return substr($mobile,0,3)."**";
        }
        $head=substr($mobile,0,3);
        $foot=substr($mobile,strlen($mobile)-4,4);
        $len=strlen($mobile)-7;
        $center=str_pad('',$len,'*');
        return $head.$center.$foot;
    }
    /**
     * 判断字符串是否为空
     * @param string $str 需要判断的字符串
     * @return bool 如果字符串为空则返回true，否则返回false
     */
    public function isDate(string $date): bool
    {
        //匹配日期格式
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
        {
            //检测是否为日期,checkdate为月日年
            if(checkdate($parts[2],$parts[3],$parts[1]))
                return true;
            else
                return false;
        }
        else
            return false;
    }

    /**
     * PHP截取文字长度
     * @param string $string 需要截取的字符串
     * @param int $length 需要截取的长度
     * @param string $end 截取后字符串末尾添加的字符串，默认为换行符
     * @param bool $once 是否只截取一次，默认为false，如果为true则只截取一次并返回结果
      * @return string 返回截取后的字符串
     */
    public function chunkSplit(string $string, int $length, string $end="\n", bool $once = false): string{
        $array = array();
        $strlen = mb_strlen($string);
        while($strlen){
            $array[] = mb_substr($string, 0, $length, "utf-8");
            if($once)
                return $array[0] . $end;
            $string = mb_substr($string, $length, $strlen, "utf-8");
            $strlen = mb_strlen($string);
        }
        return implode($end, $array);
    }

    /**
     * 判断字符串是否是序列化后的数据
     * @param mixed $data 需判断的字符串
     * @return bool
     */
    public function isSerialized(string $data): bool
    {
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data)
            return true;
        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        }
        return false;
    }

    /**
     * 判断字符串是否是手机号
     * @param string $str 字符串
     * @return bool
     */
    public function isChinesePhone(string $str): bool
    {
        if($this->isEmpty($str)){
            return false;
        }
        return preg_match("/^1[3456789]\d{9}$/", $str)||preg_match("/^\+861[3456789]\d{9}$/", $str);
    }

    /**
     * 判断字符串是否是手机号
     * @param string $str 字符串
     * @return bool
     */
    public function isPhone($str): bool
    {
        if($this->isEmpty($str)){
            return false;
        }
        if (preg_match("/^\+861[3456789]\d{9}$/", $str) || preg_match("/^1[3456789]\d{9}$/", $str)) { //普通大陆手机号
            return true;
        } else {
            if (preg_match('/^0\d{2,3}-\d{6,8}$/', $str)||preg_match('/^400(-\d{3,4}){2}$/', $str)) { //含区号的座机号码
                return true;
            }
            if (preg_match('/400-\d{3,4}-\d{2,4}/', $str)) { //400号码
                return true;
            }
            $str=str_replace('-','',$str);
            if (preg_match('#^(852)\d{7,9}$#', $str)) {//香港号码含区号
                return true;
            }
            if (preg_match('#^(851)\d{7,9}$#', $str)) { //澳门号码含区号
                return true;
            }
            if (preg_match('#^(\+852)\d{7,9}$#', $str)) {//香港号码含区号
                return true;
            }
            if (preg_match('#^(\+851)\d{7,9}$#', $str)) { //澳门号码含区号
                return true;
            }
            if (preg_match('#^[6|9|5|8]\d{7,8}$#', $str)) { //香港/澳门号码（无区号）
                return true;
            }
            return false;
        }
    }

    /**
     * 删除某个文件夹
     * @param string $path 文件夹路径
     * @return void
     */
    public function deleteDir(string $path)
    {
        if (is_dir($path)) {
            //扫描一个目录内的所有目录和文件并返回数组
            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                //排除目录中的当前目录(.)和上一级目录(..)
                if ($dir != '.' && $dir != '..') {
                    //如果是目录则递归子目录，继续操作
                    $sonDir = $path . '/' . $dir;
                    if (is_dir($sonDir)) {
                        //递归删除
                        $this->deleteDir($sonDir);
                        //目录内的子目录和文件删除后删除空目录
                        @rmdir($sonDir);
                    } else {
                        //如果是文件直接删除
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
    }


    
    /**
     * 将版本号转为数字
     * @param string $ver 版本号
     * @return float 返回转换后的版本号数字
     */
    public function GetVerId(string $ver): float
    {
        $ver = str_replace("v", "", $ver);
        $ver = str_replace("V", "", $ver);
        $arr = explode(".", $ver);
        $kstr = "";
        if(count($arr)<3){
            for($i=0;$i<3-count($arr);$i++){
                $ver.='.0';
            }
            return $this->GetVerId($ver);
        }
        for($j=0;$j<3;$j++){
            $k=$arr[$j];
            if (strlen($k) < 4) {
                $len = 4 - strlen($k);
                for ($i = 0; $i < $len; $i++) {
                    $k = "0" . $k;
                }
            }
            $kstr .= $k;
        }
        if(count($arr)==4){
            $k=$arr[3];
            if (strlen($k) < 4) {
                $len = 4 - strlen($k);
                for ($i = 0; $i < $len; $i++) {
                    $k = "0" . $k;
                }
            }
            $kstr.='.'.$k;
        }
        return floatval($kstr);
    }

    /**
     * 判断是否为身份证号
     * @param $str
     * @return bool
     */
    public function isIdCard(string $str): bool
    {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $str)) return false;
        if (!in_array(substr($str, 0, 2), $vCity)) return false;
        $str = preg_replace('/[xX]$/i', 'a', $str);
        $vLength = strlen($str);
        if ($vLength == 18) {
            $vBirthday = substr($str, 6, 4) . '-' . substr($str, 10, 2) . '-' . substr($str, 12, 2);
        } else {
            $vBirthday = '19' . substr($str, 6, 2) . '-' . substr($str, 8, 2) . '-' . substr($str, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($str, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            if ($vSum % 11 != 1) return false;
        }
        return true;
    }

    /**
     * 生成一个UUID字符串
     * @return string
     */
    public function createUUID() {
        $chars = md5(uniqid(\mt_rand(), true));
        $uuid = substr ( $chars, 0, 8 ) . '-'
            . substr ( $chars, 8, 4 ) . '-'
            . substr ( $chars, 12, 4 ) . '-'
            . substr ( $chars, 16, 4 ) . '-'
            . substr ( $chars, 20, 12 );
        return $uuid ;
    }

    /**
     * 获取客户端IP
     * @return mixed|string
     */
    public function getClientIp(): string
    {
        $ip = FALSE;
        //客户端IP 或 NONE
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("^(10│172.16│192.168).", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        //客户端IP 或 (最后一个)代理服务器 IP
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    /**
     * 生成一个不会重复的字符串
     * @return string
     */
    public function makeToken(): string
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str); //加密
        return $str;
    }

    /**
     * 密码加盐加密
     * @param $pwd 密码
     * @param $salt 加密盐
     * @return string
     */
    public function PasswordSalt(string $pwd, string $salt): string
    {
        return md5(md5($pwd . $salt) . $salt);
    }

    /**
     * PHP格式化字节大小
     * @param float $size 字节数
     * @param string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
    public function formatBytes(float $size, string $delimiter = ''): string
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 将数字转为两位小数字符串
     * @param float $value 数字
     * @return string
     */
    public function fix(float $value,int $decimals = 2): string
    {
        return sprintf('%.' . $decimals . 'f', $value);
    }
    
    /**
     * 时间戳格式化
     * @param int $time
     * @param string $format 默认'Y-m-d H:i:s'，x代表毫秒
     * @return string 完整的时间显示
     */
    public function timeFormat(int $time = NULL, $format = 'Y-m-d H:i:s')
    {
        $usec = $time = $time === null ? '' : $time;
        if (str_contains($time, '.')) {
            list($usec, $sec) = explode(".", $time);
        } else {
            $sec = 0;
        }
        return $time != '' ? str_replace('x', $sec, date($format, intval($usec))) : '';
    }

    /**
     * 字符串转时间
     * @param string $string
     * @param DateTimeZone|null $timeZone
     * @return DateTime
     */
    public function parseDateTime(string $string, ?DateTimeZone $timeZone = null): DateTime
    {
        $date = new DateTime(
            $string,
            $timeZone ? $timeZone : new DateTimeZone('UTC')
        );
        if ($timeZone) {
            $date->setTimezone($timeZone);
        }
        return $date;
    }


    /**
     * 字符串转日期
     * @param DateTime $datetime
     * @return DateTime
     */
    public function stripTime(DateTime $datetime): DateTime
    {
        return new DateTime($datetime->format('Y-m-d'));
    }

    /**
     * 间隔时间段格式化
     * @param int $time 时间戳
     * @param string $format 格式 【d：显示到天 i显示到分钟 s显示到秒】
     * @return string
     */
    public function timeTrans(int $time, string $format = 'd'): string
    {
        $now = time();
        $diff = $now - $time;
        if ($diff < 60) {
            return '1分钟前';
        } else if ($diff < 3600) {
            return floor($diff / 60) . '分钟前';
        } else if ($diff < 86400) {
            return floor($diff / 3600) . '小时前';
        }
        $yes_start_time = strtotime(date('Y-m-d 00:00:00', strtotime('-1 days'))); //昨天开始时间
        $yes_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-1 days'))); //昨天结束时间
        $two_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-2 days'))); //2天前结束时间
        $three_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-3 days'))); //3天前结束时间
        $four_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-4 days'))); //4天前结束时间
        $five_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-5 days'))); //5天前结束时间
        $six_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-6 days'))); //6天前结束时间
        $seven_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('-7 days'))); //7天前结束时间

        if ($time > $yes_start_time && $time < $yes_end_time) {
            return '昨天';
        }

        if ($time > $yes_start_time && $time < $two_end_time) {
            return '1天前';
        }

        if ($time > $yes_start_time && $time < $three_end_time) {
            return '2天前';
        }

        if ($time > $yes_start_time && $time < $four_end_time) {
            return '3天前';
        }

        if ($time > $yes_start_time && $time < $five_end_time) {
            return '4天前';
        }

        if ($time > $yes_start_time && $time < $six_end_time) {
            return '5天前';
        }

        if ($time > $yes_start_time && $time < $seven_end_time) {
            return '6天前';
        }

        switch ($format) {
            case 'd':
                $show_time = date('Y-m-d', $time);
                break;
            case 'i':
                $show_time = date('Y-m-d H:i', $time);
                break;
            case 's':
                $show_time = date('Y-m-d H:i:s', $time);
                break;
            default:
                $show_time = date('Y-m-d H:i:s', $time);
        }
        return $show_time;
    }

    /**
     * 判断字符串是否是URL
     * @param string $str
     * @return bool
     */
    public function isUrl(string $str): bool
    {
        if (filter_var($str, FILTER_VALIDATE_URL) !== false) {
            return true;
        }
        return false;
    }

    /**
     * IP地址转数字
     * @param $ip
     * @return float|int
     */
    public function ipton(string $ip): float|int
    {
        $ip_arr = explode('.', $ip);//分隔ip段
        $ipstr = '';
        foreach ($ip_arr as $value) {
            $iphex = dechex($value);//将每段ip转换成16进制
            if (strlen($iphex) < 2)//255的16进制表示是ff，所以每段ip的16进制长度不会超过2
            {
                $iphex = '0' . $iphex;//如果转换后的16进制数长度小于2，在其前面加一个0
                //没有长度为2，且第一位是0的16进制表示，这是为了在将数字转换成ip时，好处理
            }
            $ipstr .= $iphex;//将四段IP的16进制数连接起来，得到一个16进制字符串，长度为8
        }
        return hexdec($ipstr);//将16进制字符串转换成10进制，得到ip的数字表示
    }

    /**
     * 判断字符串是否为邮箱
     * @param $email
     * @return false|int
     */
    public function isEmail(string $email): bool
    {
        $pattern_test = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        return preg_match($pattern_test, $email) === 1;
    }

    /**
     * 判断网址是否是domain
     * @param $domain
     * @return bool
     */
    public function isDomain(string $domain): bool
    {
        return !empty($domain) && (str_contains($domain, '--') === false) &&
        preg_match('/^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.tv|\.org\.cn|\.org|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz)$/i', $domain) ? true : false;
    }

    /**
     * 转驼峰
     */
    public function toPascalCase(string $string): string {
        // 转换为小驼峰
        $camelCase = str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $string))));
        // 确保第一个单词首字母大写
        return ucfirst($camelCase);
    }

    /**
     * 大驼峰转为下划线
     */
    public function toSnakeCase($string) {
        // 转换为小驼峰
        $camelCase = str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $string))));
        // 转换为下划线
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCase));
    }

    /**
     * 判断字符串是否是IP地址（支持IPv6）
     * @param $str
     * @return bool
     */
    public function isIP(string $str): bool
    {
        if (filter_var($str, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否为空
      * @param mixed $value 需要判断的值
      * @return bool 如果值为空则返回true，否则返回false
     */
    public function isEmpty(mixed $value): bool {
        if($value===null){
            return true;
        }
        if(is_numeric($value)){
            return false;
        }
        if(is_array($value)){
            return false;
        }
        if(is_string($value)&&$value==''){
            return true;
        }
        return false;
    }

    /**
     * 获取姓名中的姓氏和名字
     * @param string $fullname 全名
     * @return array 包含姓氏和名字的数组，前一个元素为姓，后一个元素为名
     */
    public function splitName(string $fullname): array {
        $hyphenated = array('欧阳','太史','端木','上官','司马','东方','独孤','南宫','万俟','闻人','夏侯','诸葛','尉迟','公羊','赫连','澹台','皇甫',
            '宗政','濮阳','公冶','太叔','申屠','公孙','慕容','仲孙','钟离','长孙','宇文','城池','司徒','鲜于','司空','汝嫣','闾丘','子车','亓官',
            '司寇','巫马','公西','颛孙','壤驷','公良','漆雕','乐正','宰父','谷梁','拓跋','夹谷','轩辕','令狐','段干','百里','呼延','东郭','南门',
            '羊舌','微生','公户','公玉','公仪','梁丘','公仲','公上','公门','公山','公坚','左丘','公伯','西门','公祖','第五','公乘','贯丘','公皙',
            '南荣','东里','东宫','仲长','子书','子桑','即墨','达奚','褚师');
        $vLength = mb_strlen($fullname, 'utf-8');
        $lastname = '';
        $firstname = '';//前为姓,后为名
        if($vLength > 2){
            $preTwoWords = mb_substr($fullname, 0, 2, 'utf-8');//取命名的前两个字,看是否在复姓库中
            if(in_array($preTwoWords, $hyphenated)){
                $lastname = $preTwoWords;
                $firstname = mb_substr($fullname, 2, 10, 'utf-8');
            }else{
                $lastname = mb_substr($fullname, 0, 1, 'utf-8');
                $firstname = mb_substr($fullname, 1, 10, 'utf-8');
            }
        }else if($vLength == 2){//全名只有两个字时,以前一个为姓,后一下为名
            $lastname = mb_substr($fullname ,0, 1, 'utf-8');
            $firstname = mb_substr($fullname, 1, 10, 'utf-8');
        }else{
            $lastname = $fullname;
        }
        return array($lastname, $firstname);
    }

    /**
     * 将时间戳转换为人类可读的日期时间格式
     * @param int $timestamp 需要转换的时间戳
     * @return string 人类可读的日期时间格式
     */
    public function humanizeDateTime(int $timestamp): string {
        $now = new DateTime();
        $date = new DateTime('@' . $timestamp+8*3600);
    
        $interval = $now->diff($date);
    
        if ($interval->d == 0) {
            // 今天
            return '今天 ' . $date->format('H:i');
        } elseif ($interval->d == 1) {
            // 明天
            return '明天 ' . $date->format('H:i');
        } elseif ($interval->d == -1) {
            // 昨天
            return '昨天 ' . $date->format('H:i');
        } elseif ($interval->y == 0) {
            // 今年
            return $date->format('m-d H:i');
        } else {
            // 其他年份
            return $date->format('Y-m-d H:i');
        }
    }
}