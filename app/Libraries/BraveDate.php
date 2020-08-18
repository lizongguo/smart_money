<?php
namespace App\Libraries;

/**
 * BraveDate
 *
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-1-18 15:38:50
 * @copyright   Copyright(C) bravesoft Inc.
 */
class BraveDate
{
    /**
     * jp date to word date
     * @param type $jpDateStr
     * @param string $type
     * @return boolean|string
     */
    public function jpDateToWordDate($jpDateStr, $type = 'date')
    {
        $match = null;
        $jpdate = preg_match("/^([^\d]+)([\d]+)([^0-9]+)([\d]+)([^0-9]+)([\d]+)([^0-9]+)$/i", $jpDateStr, $match);
        if (!$jpdate) {
            return false;
        }
        if (!in_array($type, array('date', 'num'))) {
            $type = 'date';
        }
        $year = 0;
        switch ($match[1]) {
            case '平成':
                $yearNum = 4;
                $year = 1988 + $match[2];
                break;
            case '昭和':
                $yearNum = 3;
                $year = 1925 + $match[2];
                break;
            case '大正':
                $yearNum = 2;
                $year = 1912 + $match[2];
                break;
            case '明治':
                $yearNum = 1;
                $year = 1867 + $match[2];
                break;
        }
        if ($type == 'date') { //Y-m-d。
            $date = "{$year}-{$match[4]}-{$match[6]}";
        } elseif ($type == 'num') { //num
            $date = $yearNum . $match[2] . $match[4] . $match[6];
        }
        return $date;
    }

    //公元日期 转换 jp日期 $type = date|num
    /**
     * Word Date To JP Date
     * @param type $wordDateStr
     * @return string
     */
    public function wordDateTojpDate($wordDateStr)
    {
        $y = date('Y', strtotime($wordDateStr));
        if ($y - 1988 > 0) {
            $yearStr = '平成' . ($y - 1988) . '年';
        } elseif ($y - 1925 > 0) {
            $yearStr = '昭和' . ($y - 1925) . '年';
        } elseif ($y - 1912 > 0) {
            $yearStr = '大正' . ($y - 1912) . '年';
        } elseif ($y - 1867 > 0) {
            $yearStr = '明治' . ($y - 1867) . '年';
        } else {
            return $wordDateStr;
        }
        $date = $yearStr . date('m月d日', strtotime($wordDateStr));
        return $date;
    }
}
