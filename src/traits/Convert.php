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

}