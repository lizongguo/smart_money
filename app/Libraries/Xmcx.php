<?php
namespace App\Libraries;
use Log;
class Xmcx
{
    public $postCharset = "UTF-8";
    public $config = null;
    public $createEnquiryUrl = null;
    public $queryXubaoUrl = null;
    /**
     * 小马报价接口
     */

    function __construct()
    {
        $this->config = config('xmcx');
        $this->createEnquiryUrl = $this->config['url'].'/xmcxapi/webService/enquiry/createEnquirySync?api_key='.$this->config["apiKey"];
        $this->queryXubaoUrl = $this->config['url'].'/xmcxapi/webService/enquiry/queryXubao?api_key='.$this->config["apiKey"];
    }

    /*询价
    data = [
        'insurance' => [
            [
                'insurance_type' => 2  1交强2商业
                'xmcx_code' => 1001 小马编号
                'title' => '第三方责任险'  保险名
                'compensation' => 0   是否不计免赔
                'price' => 1000000   选择的要素保额
                'element' => '100万'   选择的要素名字
            ],
            [
                'insurance_type' => 2  1交强2商业
                'xmcx_code' => 1001 小马编号
                'title' => '第三方责任险'  保险名
                'compensation' => 0   是否不计免赔
                'price' => 1000000   选择的要素保额
                'element' => '100万'   选择的要素名字
            ]
        ],
        'car_num' => '浙 G786VL',
        'owner' => '刘钢',
        ...
    ]

    */
    public function createEnquiry($data, &$error = null){
        $config = $this->config;

        $url = $this->createEnquiryUrl;

        if(empty($data['company_xmcx_code'])){
            $error = '暂不支持该保险公司';
            return false;
        }

        $insurances = [];
        $forcePremium = 0;
        foreach ($data['insurance'] as $v){
            if($v['insurance_type'] == 1){//交强险
                $forcePremium = 1;
            }else if($v['insurance_type'] == 2){ //商业险
                $insurances[] = [
                    "insuranceId" => $v['xmcx_code'],
                    "insuranceName" => $v['title'],
                    //"quotesPrice" => 0,
                    "compensation" => empty($v['deduction']) ? false : true,
                    "price" => $v['price'],
                    "amountStr" => $v['element'],
                    "isToubao" => 1
                ];
            }
        }

        $postData = [
            "createEnquiryParams"=>[
                "licenseNumber"=> $data['car_num'],//"浙 G786VL",
                "idCard"=> $data['licence'],
                "ownerName"=> $data['owner'],
                "cityName"=> $data['city'],
                "cityCode"=> $data['city_code'],
                "insuranceCompanyName"=> $data['company_xmcx_code'],
                "insuranceStartTime"=> empty($data['insurance_begin_time']) ? time() : $data['insurance_begin_time'],
                "forceInsuranceStartTime"=> empty($data['force_insurance_begin_time']) ? time() : $data['force_insurance_begin_time'],
                "transferDate"=> empty($data['transfer_date']) ? 0 : $data['transfer_date'] ,
                "carTypeCode"=> "02",  //01 大型 02 小型,
                "yy" => preg_match("#非运营#isu", $data['nature']) ? false : true,

                "branchCode"=> $config["branchCode"],

                "requestHeader"=> $data['order_sn'],
                "insurancesList"=> [
                    [
                        "schemeName"=> "default",
                        "forcePremium"=> [
                            "isToubao"=> $forcePremium,
                        ],
                        "insurances"=> $insurances,
                    ]
                ],
            ],
            "mobilePhone" => $config["mobilePhone"],
        ];
        Log::info($postData);
        $resp = "";
        try {
            $resp = self::curl($url, $postData);
        } catch (Exception $e) {
            $error = "接口请求失败";
            return false;
        }
        if(empty($resp)){
            $error = "接口调用失败";
            return false;
        }
        $respObject = json_decode($resp);
        Log::info($resp);
        if(isset($respObject->errorMsg) && $respObject->errorMsg && isset($respObject->errorMsg->code) && strtolower($respObject->errorMsg->code) == "success"){
            return true;
        }
        $error = "询价失败了。";
        return false;
    }

    /*续保信息
    data = [
        'car_num' => '浙 G786VL',
        'owner' => '刘钢',
        'city_code' => '510010',
    ]
    */
    public function queryXubao($data){
        $url = $this->queryXubaoUrl;

        $postData = [
            "carInfo"=>[
                "licenseNumber"=> $data['car_num'],//"浙 G786VL",
                "ownerName"=> $data['owner'],
                "carTypeCode"=> '02',
            ],
            "cityCode" => $data["city_code"],
        ];
        try {
            $resp = self::curl($url, $postData);
        } catch (Exception $e) {
            return "接口请求失败";
        }
        if (!empty($resp)) {
            return json_decode($resp);
        }
        return "接口调用失败";
    }

    protected static function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $postBodyString = "";
        $encodeArray = Array();
        foreach ($postFields as $k => $v) {
            $encodeArray[] = $k. "=". urlencode((is_array($v) || is_object($v)) ? json_encode($v, JSON_UNESCAPED_UNICODE) : (string)$v);
        }
        $postBodyString = join('&', $encodeArray);

        Log::info($postBodyString, []);

        //$postBodyString = rawurlencode($postBodyString);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);

        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $reponse;
    }
}