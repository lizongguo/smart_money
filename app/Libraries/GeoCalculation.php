<?php
namespace App\Libraries;
/**
 * 经纬度计算类
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 16:40:30
 * @copyright   Copyright(C) bravesoft Inc.
 */
class GeoCalculation
{
    //地球半径 km
    const EARTH_RADIUS = 6378.137;
    
    /**
    *计算某个经纬度的周围某段距离的正方形的四个点
    *
    *@param lng float 经度
    *@param lat float 纬度
    *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为6千米 单位 km
    *@return array 正方形的四个点的经纬度坐标
    */
    public function loadGeoSquare($lng, $lat, $distance = 6)
    {
        $dlng =  2 * asin(sin($distance / (2 * self::EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/self::EARTH_RADIUS;
        $dlat = rad2deg($dlat);

        return [
            'lt' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'rt' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'lb' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'rb' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        ];
    }
    
    /**
     * 计算两点之间的距离，km
     * @param type $lat1 纬度1
     * @param type $lng1 经度1
     * @param type $lat2 纬度2
     * @param type $lng2 经度2
     * @return float $s  km
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = deg2rad($lng1) - deg2rad($lng2);
        
        $s = 2 * asin(sqrt(pow(sin($a/2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $s = $s * self::EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }

}
