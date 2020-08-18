<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\BLogger;
use App\Models\User;
use App\Models\Resume;
use App\Models\Experience;

class UpdateOldUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'UpdateOldUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新新系统用户';
    
    /**
     *
     * @var App\Models\OrderTakeout
     */
    protected $takeout = null;
    
    /**
     * @var App\Services\Meituan\PeisongService 
     */
    protected $peisongService = null;
    
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $nationality = config('code.resume.nationality');

        $resumeObj = new Resume();

        $list = $resumeObj->getList([], true);

        foreach ($list as $k => $v) {
            $resumeObj = new Resume();

            echo $v['resume_id'] . "\n";

            //resume 绑定用户
            $update = [
                'resume_id' => $v['resume_id'],
                'account_code' => 'S' . str_pad($v['user_id'], 4, '0', STR_PAD_LEFT),
            ];

            $key = array_search($v['nationality'], $nationality);
            if ($key) {
                $update['nationality_id'] = $key;
            } else {
                $update['nationality_id'] = 17;
            }

            if ($v['address'] != 1) {
                $update['address'] = 2;
            }

            if ($v['age']) {
                $update['birthday'] = date("Y-01-01", strtotime("-{$v['age']} year"));
            }

            $resumeId = $resumeObj->saveItem($update);
        }

        echo 'success';
        //BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('peisong batch is complate!');
    }
}
