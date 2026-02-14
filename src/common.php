<?php

if(!function_exists('dateStr')){
    /**
     * 将时间戳转换为日期时间字符串
     * @param int $time 时间戳，默认为当前时间
     */
    function dateStr($time=0){
        if($time==0){
            $time=time();
        }
        return date('Y-m-d H:i:s',$time);
    }
}

if(!function_exists('jsonEncode')){
    /**
     * 将数据编码为JSON字符串，保持中文字符不被转义
     * @param mixed $value 需要编码的数据
     * @return string 返回编码后的JSON字符串
     */
    function jsonEncode($value){
        return json_encode($value,JSON_UNESCAPED_UNICODE);
    }
}

if(!function_exists('randInt')){
    /**
     * 生成一个指定范围内的随机整数
     * @param int $min 最小值，默认为0
     * @param int $max 最大值，默认为PHP_INT_MAX
      * @return int 返回生成的随机整数
     */
    function randInt($min,$max){
        return random_int($min,$max);
    }
}

if(!function_exists('formatTime')){
    /**
     * 将时间戳或日期时间字符串格式化为人类可读的日期时间格式
      * @param int|string $time 时间戳或日期时间字符串，默认为当前时间
      * @return string 返回格式化后的日期时间字符串
     */
    function formatTime($time){
        if(is_numeric($time)){
            $time=dateStr($time);
        }
        $deliverTime=date('Y-m-d H:i',strtotime($time));
        if(date('Y-m-d')!=date('Y-m-d',strtotime($deliverTime))){
            return date('m-d H:i',strtotime($deliverTime));
        } else{
            return date('H:i',strtotime($deliverTime));
        }
    }
}

if(!function_exists('delfile')){
    /**
     * 删除指定路径的文件
     * @param string $file 需要删除的文件路径
     * @return void
     */
    function delfile($file){
        if(file_exists($file)){
            @unlink($file);
        }
    }
}