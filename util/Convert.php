<?php
namespace util;

/**
 * Утилита конвертации данных
*/
class Convert {

    public static function object_to_array($object){
        $result = array();
        $data = is_object($object) ? get_object_vars($object) : $object;
        foreach($data as $key => $value){
            if (!is_null($value)){
                if(is_array($value)){
                    $result[$key] = self::object_to_array($value);
                }elseif(is_object($value)){
                    $value = get_object_vars($value);
                    $result[$key] = count($value) > 0 ? self::object_to_array($value) : null;
                }else{
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
}