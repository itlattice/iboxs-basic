<?php

namespace iboxs\basic\traits;

trait File
{

    /**
     * 加锁写入文件
     * @param $file 文件路径
     * @param $text 字符串
     * @param $mode 写入方式
     * @param $timeout 最长等待时间
     * @return bool
     */
    public function file_write($file, $text, $mode = 'a+', $timeout = 5)
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



}