<?php


namespace Gongyao\Testcomposer\Pinyin;


class helper
{
    //中文字符串
    private $string = '';
    //拼音
    private $pinyin = '';
    private $encoding = 'UTF-8';
    //拼音首字母
    private $short_pinyin = '';
    //最长的拼音
    private $max_pinyin = 6;
    //单个汉字拼音的字典
    private $dic;
    //所有的汉字拼音
    private $total_pinyin;

    public function __construct()
    {
        $this->dic = dict::$dic;
        $this->total_pinyin = dict::$total_pinyin;
    }

    /**
     * 字符串拆分成单个字的数组
     * @param $string
     * @return array
     */
    private static function mbStringToArray($string)
    {
        $stop = mb_strlen($string, 'utf-8');
        $result = [];

        for ($idx = 0; $idx < $stop; $idx++) {
            $result[] = mb_substr($string, $idx, 1, 'utf-8');
        }

        return $result;
    }

    /**
     * 汉字转拼音
     * @param $string
     * @param $encoding
     */
    private function chineseToPinyin($string, $encoding)
    {
        $words = self::mbStringToArray(mb_convert_encoding($string, 'utf-8', $encoding));
        $this->string = $string;
        $this->encoding = $encoding;
        $this->pinyin = ''; // 拼音全程
        $this->short_pinyin = '';
        foreach ($words as $v) {
            $tmp = $this->dic[$v] ?? $v;
            $this->pinyin .= $tmp;
            $this->short_pinyin .= mb_substr($tmp, 0, 1, $encoding);
        }
    }

    /**
     * 获取拼音
     * @param $string
     * @param string $encoding
     * @return string
     */
    public function getPinyin($string, $encoding = 'utf-8')
    {
        if ($string != $this->string || $encoding != $this->encoding) {
            $this->chineseToPinyin($string, $encoding);
        }
        return $this->pinyin;
    }

    /**
     * 获取拼音缩写
     * @param $string
     * @param string $encoding
     * @return string
     */
    public function getShortPinyin($string, $encoding = 'utf-8')
    {
        if ($string != $this->string || $encoding != $this->encoding) {
            $this->chineseToPinyin($string, $encoding);
        }
        return $this->short_pinyin;
    }


//    /**
//     * 将拼音全称缩写
//     * @param $string
//     * @return array
//     */
//    public function getShortByPinyin($string)
//    {
//        $shorts = [];
//
//        #1、去除其中的空格符之类的
//        $string = str_replace(
//            ["\r\n", "\r", "\n", ' '],
//            "",
//            $string
//        );
//
//        #2、拼音转小写
//        $string = strtolower($string);
//
//        #3、判断是否为空
//        if (empty($string)) {
//            return $shorts;
//        }
//
//        #4、找到所有的简称
//        $strs = str_split($string);
//        $count = count($strs);
//        while (empty($shorts) || self::is_continue($shorts, $count)) {
//            self::loop_pinyin_short($shorts, $count, $strs);
//        }
//
//        #5、获取最短的简称
//        $ret = [];
//        $min = 0;
//        foreach ($shorts as $short) {
//            $temp = strlen($short['short']);
//
//            if (empty($min)) {
//                $min = $temp;
//                $ret[] = $short['short'];
//            } elseif ($temp < $min) {
//                $min = $temp;
//                $ret = [];
//                $ret[] = $short['short'];
//            } elseif ($temp == $min) {
//                $ret[] = $short['short'];
//            }
//        }
//
//        return $ret;
//    }
//
//    /**
//     * 轮询获取拼音简称
//     * @param $shorts
//     * @param $count
//     * @param $strs
//     */
//    private static function loop_pinyin_short(&$shorts, $count, $strs)
//    {
//        #第一次获取
//        if (empty($shorts)) {
//            $rets = self::one_pinyin_short($strs);
//            if (!empty($rets)) {
//                foreach ($rets as $ret) {
//                    $shorts[] = [
//                        'cur' => $ret['key'] + 1,
//                        'short' => $ret['value']
//                    ];
//                }
//            } else {
//                $shorts[] = [
//                    'short' => $strs[0],
//                    'cur' => 1,
//                ];
//            }
//        } else {
//            foreach ($shorts as $key => &$short) {
//                if ($short['cur'] >= $count) {
//                    continue;
//                }
//
//                $rets = self::one_pinyin_short($strs, $short['cur']);
//                if (!empty($rets)) {
//                    foreach ($rets as $ret) {
//                        $shorts[] = [
//                            'cur' => $ret['key'] + 1,
//                            'short' => $short['short'] . $ret['value'],
//                        ];
//                    }
//                    unset($shorts[$key]);
//                } else {
//                    $short['short'] .= $strs[$short['cur']];
//                    $short['cur'] += 1;
//                }
//            }
//        }
//    }
//
//    /**
//     * 是否继续计算简称
//     * @param $shorts
//     * @param $count
//     * @return bool
//     */
//    private static function is_continue($shorts, $count)
//    {
//        if (empty($shorts)) {
//            return false;
//        }
//
//        foreach ($shorts as $short) {
//            if ($short['cur'] < $count) {
//                return true;
//            }
//        }
//
//        return false;
//    }

//    /**
//     * 获取一个简称
//     * @param $strs
//     * @param $key
//     * @return array
//     */
//    private static function one_pinyin_short($strs, $key = 0)
//    {
//        $i = 0;
//        $pin = '';
//
//        $pins = [];
//
//        while ($i < self::$max_pinyin) {
//            $temp = $key + $i;
//
//            if (!isset($strs[$temp])) {
//                return $pins;
//            }
//
//            $pin .= $strs[$temp];
//            if (isset(self::$total_pinyin[$pin])) {
//                $pins[] = [
//                    'value' => self::$total_pinyin[$pin],
//                    'key' => $temp,
//                ];
//            }
//
//            ++$i;
//        }
//
//        return $pins;
//    }

    /**
     * @param $ch
     * @return bool
     */
    public function isHan($ch)
    {
        return isset($this->dic[$ch]);
    }
}