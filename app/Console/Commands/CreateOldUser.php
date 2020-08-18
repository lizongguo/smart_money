<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\BLogger;
use App\Models\User;
use App\Models\Resume;
use App\Models\Experience;

class CreateOldUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'CreateOldUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老用户转换为新系统用户';
    
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
        //BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('peisong  batch is start!');

        $resumeObj = new Resume();

        $list = $resumeObj->getList([], true);

        foreach ($list as $k => $v) {
            $resumeObj = new Resume();
            $userObj = new User();
            $experienceObj = new Experience();

            $info = $userObj->where("email", $v['email'])->first();
            if ($info) {
                continue;
            }

            echo $v['email'] . "\n";
            $password = substr(md5(time()), 0, 6);

            $userInsert = [
                'email' => $v['email'],
                'nickname' => $v['name'],
                'password' => \Hash::make($password),
                'created_at' => date("Y-m-d H:i:s"),
            ];
            $userId = $userObj->saveItem($userInsert);

            //resume 绑定用户
            $update = [
                'resume_id' => $v['resume_id'],
                'user_id' => $userId,
            ];

            $resumeId = $resumeObj->saveItem($update);

            //填充履历
            $experience = [
                'user_id' => $userId,
                'name' => $v['name'],
                'sex' => $v['sex'],
                'nationality_id' => $v['nationality_id'],
                'nationality' => $v['nationality'],
                'address' => $v['address'],
                'address_id' => $v['address_id'],
                'cell_phone' => $v['cell_phone'],
                'email' => $v['email'],
                //'visa_type' => $data['visa_type'],
                //'visa_other' => $data['visa_other'],
                //'visa_term' => $data['visa_term'],
                'pr_other' => $v['pr_other'],
            ];
            $experienceId = $experienceObj->saveItem($experience);

        }

        echo 'success';
        //BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('peisong batch is complate!');
    }
}
