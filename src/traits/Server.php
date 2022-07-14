<?php

namespace iboxs\basic\traits;

trait Server
{
    /**
     * 获取服务器信息
     * @param string $key 项
     * @return array|mixed
     */
   public function get_system_info(string $key='')
    {
        $system = [
            'os' => PHP_OS,
            'php' => PHP_VERSION,
            'upload_max_filesize' => get_cfg_var("upload_max_filesize") ? get_cfg_var("upload_max_filesize") : "不允许上传附件",
            'max_execution_time' => get_cfg_var("max_execution_time") . "秒 ",
        ];
        if (empty($key)) {
            return $system;
        } else {
            return $system[$key];
        }
    }

}