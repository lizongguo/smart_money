<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Desk\QrcodeService;

class GenerateDeskQrCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'qrcode:desk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取桌面二维码的qrcode';
    
    /**
     *
     * @var App\Services\Desk\QrcodeService 
     */
    protected $service = null;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(QrcodeService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $this->info('generate desk qrcode is start!');
        $rs = $this->service->runDeskQrCode();
        if ($rs === true) {
            $this->info('generate desk qrcode is complate!');
        } else {
            $this->info('generate desk qrcode is failed!');
        }
        $rs = $this->service->runShopQrCode();
        if ($rs === true) {
            $this->info('generate shop qrcode is complate!');
        } else {
            $this->info('generate shop qrcode is failed!');
        }
        
    }
}
