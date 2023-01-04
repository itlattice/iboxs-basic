<?php
namespace iboxs\basic\traits;
trait Str
{
    /**
     * 获取随机字符串
     * @param int $length 随机字符串长度
     * @return string
     */
    public function GetRandStr(int $length = 8): string
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randStr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randStr .= $str[$num];
        }
        return $randStr;
    }

    public function phoneHandle($mobile){
        if(strlen($mobile)<8){
            return substr($mobile,0,3)."**";
        }
        $head=substr($mobile,0,3);
        $foot=substr($mobile,strlen($mobile)-4,4);
        $len=strlen($mobile)-7;
        $center=str_pad('',$len,'*');
        return $head.$center.$foot;
    }

    public function chunkSplit($string, $length, $end="\n", $once = false){
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
    public function isSerialized($data): bool
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
    public function isPhone(string $str): bool
    {
        if (preg_match("/^1[34578]\d{9}$/", $str)) {
            return true;
        } else {
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
     * 判断字符串结尾是否是相关字符(PHP8.0可直接使用str_ends_with()函数)
     * @param string $str 原字符串
     * @param string $search 结尾字符串
     * @return bool
     */
    public function endWith(string $str, string $search): bool
    {
        if (strlen($search) > strlen($str)) {
            return false;
        }
        return substr($str, strlen($str) - strlen($search), strlen($search)) == $search;
    }

    /**
     * 判断字符串开头
     * @param string $str 字符串
     * @param string $search 需判断的开头字符串
     * @return bool
     */
    public function startWith(string $str, string $search): bool
    {
        if (strlen($search) > strlen($str)) {
            return false;
        }
        return substr($str, 0, strlen($search)) == $search;
    }

    /**
     * 将版本号转为数字
     * @param $ver 版本号
     * @return string
     */
    public function GetVerId($ver)
    {
        $ver = str_replace("v", "", $ver);
        $ver = str_replace("V", "", $ver);
        $arr = explode(".", $ver);
        $kstr = "";
        foreach ($arr as $k) {
            if (strlen($k) < 4) {
                $len = 4 - strlen($k);
                for ($i = 0; $i < $len; $i++) {
                    $k = "0" . $k;
                }
            }
            $kstr .= $k;
        }
        return $kstr;
    }

    /**
     * 判断是否为身份证号
     * @param $str
     * @return bool
     */
    public function isIdCard($str)
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
     * 下载文件
     * @param $file_url 下载地址
     * @param $save_to 保存位置+文件名称
     * @return void
     */
    public function downLoadFile($file_url, $save_to)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
    }

    /**
     * 获取客户端IP
     * @return mixed|string
     */
    public function GetIP()
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
    public function make_token()
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
    public function set_password($pwd, $salt)
    {
        return md5(md5($pwd . $salt) . $salt);
    }

    /**
     * PHP格式化字节大小
     * @param mixed $size 字节数
     * @param string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * PHP截取文字长度
     * @return string
     */
   public function sub_str($str, $len = 20)
    {
        $strlen = strlen($str) / 3;#在编码utf8下计算字符串的长度，并把它交给变量$strlen
        #echo $strlen;#输出字符串长度
        if ($strlen < $len) {
            return $str;
        } else {
            return mb_substr($str, 0, $len, "utf-8") . "...";
        }
    }

    /**
     * 将数字转为两位小数字符串
     * @param $value 数字
     * @return string
     */
    public function fix2($value)
    {
        return sprintf('%.2f', $value);
    }

    /**
     * 时间戳格式化
     * @param int $time
     * @param string $format 默认'Y-m-d H:i'，x代表毫秒
     * @return string 完整的时间显示
     */
    public function time_format($time = NULL, $format = 'Y-m-d H:i:s')
    {
        $usec = $time = $time === null ? '' : $time;
        if (strpos($time, '.') !== false) {
            list($usec, $sec) = explode(".", $time);
        } else {
            $sec = 0;
        }
        return $time != '' ? str_replace('x', $sec, date($format, intval($usec))) : '';
    }

    /**
     * 字符串转时间
     * @param $string
     * @param $timeZone
     * @return DateTime
     */
    public function parseDateTime($string, $timeZone = null)
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
     * @param $datetime
     * @return DateTime
     */
    public function stripTime($datetime)
    {
        return new DateTime($datetime->format('Y-m-d'));
    }

    /**
     * 间隔时间段格式化
     * @param int $time 时间戳
     * @param string $format 格式 【d：显示到天 i显示到分钟 s显示到秒】
     * @return string
     */
    public function time_trans($time, $format = 'd')
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
     * @param $str
     * @return bool
     */
    public function isUrl($str)
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
    public function ipton($ip)
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
    public function isEmail($email)
    {
        $pattern_test = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        return preg_match($pattern_test, $email);
    }

    /**
     * 判断网址是否是domain
     * @param $domain
     * @return bool
     */
    public function is_domain($domain)
    {
        return !empty($domain) && strpos($domain, '--') === false &&
        preg_match('/^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.tv|\.org\.cn|\.org|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz)$/i', $domain) ? true : false;
    }

    /**
     * 判断字符串是否是IP地址（支持IPv6）
     * @param $str
     * @return bool
     */
    public function is_ip($str)
    {
        if (filter_var($str, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmpty($value){
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
}
