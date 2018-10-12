<?php

namespace Alonexy\Helpers;

use Carbon\Carbon;

Class HpFuns
{
    /**
     * @name 生成随机字符串
     * @param int $length 要生成的随机字符串长度
     * @param string $type 随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
     * @return string
     */
    public static function randCode($length = 7, $type = 0)
    {
        $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
        if ($type == 0) {
            array_pop($arr);
            $string = implode("", $arr);
        }
        elseif ($type == "-1") {
            $string = implode("", $arr);
        }
        else {
            $string = $arr[$type];
        }
        $count = strlen($string) - 1;
        $code  = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $string[rand(0, $count)];
        }
        return $code;
    }

    /**
     * @name 验证手机号是否正确
     * @param INT $mobile
     */
    public static function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    /**
     * @name 截取中文字符串
     * @param $text
     * @param $length
     * @return string
     */
    public static function subtext($text, $length)
    {
        if (mb_strlen($text, 'utf8') > $length)
            return mb_substr($text, 0, $length, 'utf8') . '...';
        return $text;
    }

    /**
     * @name PHP stdClass Object转array
     * @param $array
     * @return array
     */
    public static function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::object_array($value);
            }
        }
        return $array;
    }

    /**
     * @name 生产唯一通行编码
     * @param string $prefix
     * @return string
     */
    public function uuids($prefix = '')
    {
        $str  = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }

    /**
     * @name 判断是否是json
     * @param $string
     * @return bool
     */
    public function is_json($string)
    {
        @json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @name 二维数组排序
     * @param $arr
     * @param string $direction
     * @param string $field
     * @return mixed
     */
    public function arrays_sort_by_item($arr, $direction = 'SORT_DESC', $field = 'id')
    {
        $sort    = array(
            'direction' => $direction, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field' => $field,       //排序字段
        );
        $arrSort = array();
        foreach ($arr AS $uniqid => $row) {
            foreach ($row AS $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arr);
        }
        return $arr;
    }

    /**
     * @name 获取消息主体
     * @param string $msg
     * @param array $arr
     * @param int $code
     * @return array
     */
    public function getMessageBody($msg = '系统错误', $arr = [], $code = 0)
    {
        $data        = array();
        $data['msg'] = "{$msg}";
        if (empty($arr)) {
            $data['data'] = (object)$arr;
        }
        else {
            $data['data'] = $arr;
        }

        $data['status'] = $code;
        return $data;
    }

    /**
     * @name 判断是否是时间格式
     * @param $dateTime
     * @return bool
     */
    public function isDateTime($dateTime)
    {
        $ret = strtotime($dateTime);
        return $ret !== FALSE && $ret != -1;
    }

    /**
     * 获取时间差
     * @param $STime
     * @param $ETime
     * @param $formtNum
     * @return string
     */
    public function getCycleTime($STime,$ETime,$formtNum)
    {
        if(!isDateTime($STime)){
            throw new \Exception('$STime Is Err Date');
        }
        if(!isDateTime($ETime)){
            throw new \Exception('$ETime Is Err Date');
        }
        setlocale(LC_ALL, array('zh_CN.UTF-8', 'zh_CN.utf8', 'zh_CN'));
        $translator = Carbon::getTranslator();
        $translator->setMessages(
            'zh_CN', array(
            'year' => ':count年',
            'month' => ':count个月',
            'week' => ':count周',
            'day' => ':count天',
            'hour' => ':count小时',
            'minute' => ':count分钟',
            'second' => '',
        ));
        Carbon::setLocale('zh_CN');
        $cycle                      = Carbon::parse($STime)
            ->diffForHumans(Carbon::parse($ETime), true, false, $formtNum);
        return $cycle;
    }
}
