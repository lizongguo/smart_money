<?php

/**
 * Ocr
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 14:36:54
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Aliyun;

interface Ocr
{
    /**
     * 
     * @param type $file  图片相对地址
     * @param type $type  ocr 识别类型 行驶证：cars，身份证：identity、营业执照：business，驾照：driving
     * @param type $side  #首页/副页:face/back  行驶证：cars，身份证：identity 驾照：driving 必填
     */
    public function getOcr($file, $type, $side = null);
}
