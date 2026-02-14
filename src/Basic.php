<?php
/**
 * PHP基本函数包
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\basic;

use iboxs\basic\lib\helper\Helper;
use iboxs\basic\lib\http\Http;

/**
 * @package iboxs\basic
 * @method static Http Http() Http请求方法
 * @method static string getTopDomain(string $url) 获取域名中的顶级域名
 * @method static bool isWechatBrowser() 判断请求是否来自微信浏览器
 * @method static bool isAlipayBrowser() 判断请求是否来自支付宝浏览器
 * @method static bool isMobile() 判断请求是否来自移动设备
 * @method static string getBrowserLang() 获取浏览器语言
 * @method static string getOS() 获取访客操作系统
 * @method static string GetUserAgent() 获取浏览器agent
 * @method static string GetBrowser() 获得访问者浏览器类型
 * @method static array objectToArray(object $obj) 对象转数组
 * @method static bool|string imgBase64Decode(string $base64_image_content = '', bool $save_img = false, string $file_path = '') 图片base64解码
 * @method static bool fileWrite(string $file_path, string $content, string $mode = 'a+', int $timeout = 5) 将内容写入文件
 * @method static string getFileExt(string $file) 获取文件扩展名
 * @method static string getSystemInfo(string $key='') 获取系统信息
 * @method static bool isJson(string $string) 判断字符串是否为JSON格式
 * @method static string GetRandomStr(int $length = 8) 获取随机字符串
 * @method static string|array DelEmoji(string|array $str) 删除字符串中的emoji表情
 * @method static int sameStr(string $str1, string $str2) 计算两个字符串的相同长度
 * @method static string phoneHandle(string $mobile) 处理手机号，隐藏中间4位
 * @method static bool isDate(string $date) 判断字符串是否为日期格式
 * @method static string chunkSplit(string $string, int $length, string $end="\n", bool $once = false) 截取文字长度
 * @method static bool isSerialized(string $string) 判断字符串是否为序列化格式
 * @method static bool isChinesePhone(string $str) 判断字符串是否是中国的手机号
 * @method static bool isPhone(string $phone) 判断字符串是否是手机号
 * @method static void deleteDir(string $dir) 删除目录及其下的所有文件
 * @method static float GetVerId(string $ver) 将版本号转为数字
 * @method static bool isIdCard(string $str) 判断字符串是否是身份证号码
 * @method static string createUUID() 生成一个UUID字符串
 * @method static string getClientIp() 获取客户端IP地址
 * @method static string makeToken() 生成一个不会重复的字符串
 * @method static string PasswordSalt(string $pwd, string $salt) 使用加密盐加密密码
 * @method static string formatBytes(float $size, string $delimiter = '') 格式化字节大小
 * @method static string fix(float $value) 将数字转为两位小数字符串
 * @method static string timeFormat($time = NULL, $format = 'Y-m-d H:i:s') 完整的时间显示
 * @method static DateTime parseDateTime(string $string, ?DateTimeZone $timeZone = null) 字符串转时间
 * @method static string stripTime(DateTime $datetime) 字符串转日期
 * @method static string timeTrans(int $time, string $format = 'd') 时间转字符串
 * @method static bool isUrl(string $str) 判断字符串是否是URL
 * @method static float|int ipton(string $ip) IP地址转数字
 * @method static bool isEmail(string $email) 判断字符串是否是邮箱地址
 * @method static bool isDomain(string $domain) 判断字符串是否是域名
 * @method static string toPascalCase(string $string) 下划线转大驼峰
 * @method static string toSnakeCase(string $string) 大驼峰转下划线
 * @method static bool isIP(string $str) 判断字符串是否是IP地址
 * @method static bool isEmpty(mixed $value) 判断值是否为空
 * @method static array splitName(string $fullname) 获取中文姓名中的姓氏和名字
 * @method static string humanizeDateTime(int $timestamp) 将时间戳转换为人类可读的日期时间格式
 */
class Basic
{
    const HTTP_FORMAT_JSON='json';
    const HTTP_FORMAT_TEXT='text';

    const SYSTEM_INFO_OS='os'; //操作系统
    const SYSTEM_INFO_VERSION='version'; //PHP版本
    const SYSTEM_INFO_UPLOAD_MAX_FILESIZE='upload_max_filesize'; //最大上传文件大小
    const SYSTEM_INFO_MAX_EXECUTION_TIME='max_execution_time'; //最大执行时间

    // 调用实际类的方法
    public static function __callStatic($method, $params)
    {
        return (new Helper())->$method(...$params);
    }

    public static function Http(string $url,string $format=self::HTTP_FORMAT_JSON):Http{
        return new Http($url,$format);
    }
}
