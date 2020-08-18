<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use GuzzleHttp\Client;
use App\Libraries\BLogger;

class GenerateOrder implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $access_token = null;
    protected $client = null;
    public function __construct($access_token)
    {
        $this->access_token = $access_token;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $goods_ids = [1,2,3,4,5,9,12];
        $goods = [
            '1' => [
                49,50,51
            ],
            '2' => [
                '52'
            ],
            '3' => [
                43,44,45
            ],
            '4' => [
                39
            ],
            '5' => [
                32
            ],
            '9' => [
                46,47,48
            ],
            '12' => [
                42
            ],
        ];
        
        $header = [
            'Accept' => 'application/json',
            'app-name' => 'platform',
            'access-token' => $this->access_token
        ];
        
        $opts = [
            'headers' => $header,
            'http_errors' => false,
            'timeout' => 20,
            'version' => 1.1,
        ];
        
        $opts['form_params'] = [
            'shop_id' => 1,
            'seat_no' => rand(1, 15),
            'memo' => time(),
            'verify' => false,
        ];
        $num = rand(1, 4);
        $goodList = [];
        for ($i=1; $i<=$num; $i++) {
            $id = $goods_ids[rand(0, 6)];
            $g = $goods[$id];
            $goodList[] = [
                'product_id' => $g[rand(0, count($g) - 1)],
                'goods_num' => rand(1, 10),
            ];
        }
        $opts['form_params']['goodList'] = json_encode($goodList);
        
        $url = 'https://www.yangfugui.com/api/order/created';
        
        $logData = [
            'method' => 'post',
            'uri' => $url,
            'data' => $opts['body']
        ];
        $this->client = new Client();
        $response = $this->client->request(
            'POST',
            $url,
            $opts
        );
        if ($response->getStatusCode() != '200') {
            $logData['result'] = [
                'code' => $response->getStatusCode(), 
                'Response' => $response->getBody()
            ];
            BLogger::getLogger(BLogger::LOG_WX_API)->error($logData);
            return false;
        }
        $result = (string) $response->getBody();
        $logData['Response'] = $result;
        BLogger::getLogger('test')->info($logData);
        return true;
    }
}
