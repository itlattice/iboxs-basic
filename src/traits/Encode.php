<?php

namespace iboxs\basic\traits;

trait Encode
{
    /**
     * 更多加密相关的函数可安装：composer require iboxs/encryption
     */
    /**
     * 图片base64解码
     * @param string $base64_image_content 图片文件流
     * @param bool $save_img 是否保存图片
     * @param string $path 文件保存路径
     * @return bool|string
     */
   public function imgBase64Decode($base64_image_content = '', $save_img = false, $file_path = '')
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

}