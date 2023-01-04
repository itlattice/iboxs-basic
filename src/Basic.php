<?php
/**
 * PHP基本函数包
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\basic;

use iboxs\basic\lib\Base;

/**
 * @see \iboxs\basic\lib\Base
 * @package iboxs\basic
 * @mixin \iboxs\basic\lib\Base
 * @method static array objectToArray(object $array) 对象转Array
 * @method static string|bool imgBase64Decode(string $base64_image_content) 图片base64解码(更多加密相关的函数可安装：composer require iboxs/encryption)
 * @method static bool file_write($file, $text, $mode = 'a+', $timeout = 5) 加锁写入文件
 * @method static string getTopHost(string $url) 获取顶级域名
 * @method static false|string send_post(string $url, array $post_data) 简单发起post请求(更多请求方式或请求需要可安装：composer require iboxs/http)
 * @method static bool is_weixin() 判断请求是否来自微信
 * @method static string get_lang(string $agent) 获得访问者浏览器语言
 * @method static string get_os(string $agent) 获得访客操作系统
 * @method static string GetBrowser() 获取浏览器agent
 * @method static string browse_info(string $agent) 获得访问者浏览器
 * @method static array|mixed get_system_info(string $key='') 获取服务器信息
 * @method static string GetRandStr(int $length = 8) 获取随机字符串
 * @method static string isSerialized(mixed $str) 判断字符串是否是序列化后的数据
 * @method static string isPhone(string $str) 判断字符串是否是手机号
 * @method static void deleteDir(string $path) 删除某个文件夹
 * @method static bool endWith(string $str, string $search) 判断字符串结尾是否是相关字符(PHP8.0可直接使用str_ends_with()函数)
 * @method static bool startWith(string $str, string $search) 判断字符串开头
 * @method static int GetVerId($ver) 将版本号转为数字
 * @method static bool isIdCard($str) 判断是否为身份证号
 * @method static void downLoadFile($file_url, $save_to) 下载文件
 * @method static string GetIP() 获取客户端IP
 * @method static string make_token() 生成一个不会重复的字符串
 * @method static string set_password($pwd, $salt) 密码加盐加密
 * @method static int format_bytes($size, $delimiter = '') PHP格式化字节大小
 * @method static string sub_str($str, $len = 20) PHP截取文字长度
 * @method static string fix2($value) 将数字转为两位小数字符串
 * @method static string time_format($time = NULL, $format = 'Y-m-d H:i:s') 时间戳格式化
 * @method static DateTime parseDateTime($string, $timeZone = null) 字符串转时间
 * @method static DateTime stripTime($datetime) 字符串转日期
 * @method static string time_trans($time, $format = 'd') 间隔时间段格式化
 * @method static bool isUrl($str) 判断字符串是否是URL
 * @method static int ipton($ip) IP地址转数字
 * @method static bool isEmail($email) 判断字符串是否为邮箱
 * @method static bool is_domain($domain) 判断网址是否是domain
 * @method static bool is_ip($str) 判断字符串是否是IP地址（支持IPv6）
 * @method static bool isEmpty($val) 判断是否是空值
 * @method static string phoneHandle($phone) 将电话号码中间位置隐藏一部分
 * @method static string chunkSplit($string, $length, $end="\n", $once = false) 字符串按位置分离
 */
class Basic
{
    // 调用实际类的方法
    public static function __callStatic($method, $params)
    {
        return (new Base())->$method(...$params);
    }
}
