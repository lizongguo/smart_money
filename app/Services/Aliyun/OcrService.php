<?php

/**
 * GetCouchbaseRepository
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-16 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Aliyun;
use Log;

class OcrService implements Ocr
{
    protected $appCode = null;
    protected $headers = [];
    protected $urls = [
        'cars' => 'https://dm-53.data.aliyun.com/rest/160601/ocr/ocr_vehicle.json',
        'identity' => 'https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json',
        'business' => 'https://dm-58.data.aliyun.com/rest/160601/ocr/ocr_business_license.json',
        'driving' => 'https://dm-52.data.aliyun.com/rest/160601/ocr/ocr_driver_license.json',
    ];

    public function __construct()
    {
        $this->appCode = config('aliyun.ocr.appCode');
        //设置header值
        $this->setHeaders();
    }
    
    /**
     * 设置header默认值
     */
    protected function setHeaders() {
        array_push($this->headers, "Authorization:APPCODE " . $this->appCode);
        //根据API的要求，定义相对应的Content-Type
        array_push($this->headers, "Content-Type".":"."application/json; charset=UTF-8");
    }
    
    /**
     * 
     * @param type $url
     * @param type $body
     * @param type $method
     * @return boolean
     */
    protected function createCurl($url, $body, $method = 'POST') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$url, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $result = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $rheader = substr($result, 0, $header_size);
        $rbody = substr($result, $header_size);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($httpCode !== 200){
            Log::error(sprintf("Http error code: %d\nError msg in body: %s\n", $httpCode, $rbody));
            return false;
        }
        
        return json_decode($rbody, true);
        
    }
    
    /**
     * 
     * @param type $file  图片相对地址
     * @param type $type  ocr 识别类型 行驶证：cars，身份证：identity、营业执照：business，驾照：driving
     * @param type $side  #首页/副页:face/back
     */
    public function getOcr($file, $type, $side = null)
    {
        $path = public_path() . $file;
        //文件不存在 或者类型不存在
        if (!file_exists($path) || !isset($this->urls[$type])) {
            return false;
        }
        
        //打开文件
        if ($fp = fopen($path, "rb")) {
            $binary = fread($fp, filesize($path)); // 文件读取
            fclose($fp); 
            $base64 = base64_encode($binary); // 转码
        } else {
            return false;
        }
        
        //处理body
        $request = array(
            "image" => "$base64"
        );
        if(!empty($side) && in_array($side, ['face', 'back'])){
            $config = [
                "side" => $side
            ];
            $request["configure"] = json_encode($config);
        }
        
        $body = json_encode($request);
        
        $result = $this->createCurl($this->urls[$type], $body);
        if($result === false) {
            return false;
        }
        return $result;
    }
}
