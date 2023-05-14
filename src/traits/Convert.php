<?php

namespace iboxs\basic\traits;

trait Convert
{
    /**
     * 对象转Array
     * @param $array
     * @return array|mixed
     */
    function objectToArray($array)
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
     * 数组去重
     * @param array $data
     * @return array
     */
    public function ArrayDuplicate(Array $data){
        $result=[];
        foreach($data as $d){
            if(in_array($d,$result)){
                continue;
            }
            $result[]=$d;
        }
        return $result;
    }

    /**
     * 找出数组中最大值
     * @param $data
     * @return mixed
     */
    public function maxNum($data){
        $max=$data[0];
        foreach($data as $num){
            if($max<$num){
                $max=$num;
            }
        }
        return $max;
    }

    /**
     * 数组全部值求和
     * @param $arr
     * @return int|mixed
     */
    public function getAnd($arr){
        $result=0;
        foreach($arr as $num){
            $result+=$num;
        }
        return $result;
    }

    /**
     * 字典转普通数组
     * @param $arr
     * @param $key
     * @return array
     */
    public function getValue($arr,$key){
        $result=[];
        foreach($arr as $num){
            $result[]=$num[$key];
        }
        return $result;
    }
}