<?php

namespace App\Console\Commands;

use App\Imports\UsersImport;
use Illuminate\Console\Command;
use App\Libraries\BLogger;
use App\Models\Job;
use App\Models\Resume;
use App\Models\Experience;
use Maatwebsite\Excel\Facades\Excel;

class UpdateJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'UpdateJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '求人转化';
    
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

        $country_city = config('code.resume.country_city');

        $filePath = base_path('job_excel/job0404.xlsx');
        $array = Excel::toArray(new UsersImport, $filePath);

        unset($array[0][0]);
        foreach ($array[0] as $k => $v) {
            $jobObj = new Job();

            $jobInfo = $jobObj->where("job_code", $v[0])->first();

            if ($jobInfo) {
                $jobInfo['jp_level_2'] = 2;
                $jobInfo['jp_level'] = 2;
                $jobInfo['job_name'] = $v[1];
                $jobInfo['company_id'] = 3;
                $jobInfo['account_code'] =  'J' . str_pad($jobInfo['job_id'], 4, '0', STR_PAD_LEFT);

                if ($jobInfo['working_place']) {
                    $key = array_search($jobInfo['working_place'], $country_city);
                    if ($key) {
                        $jobInfo['prefecture'] = $key;
                    } else {
                        $jobInfo['prefecture'] = 99;
                        $jobInfo['prefecture_other'] = $jobInfo['working_place'];
                    }

                    $jobInfo['working_place'] = '';
                }

                $jobObj->saveItem($jobInfo);

                echo "job_id " . $jobInfo['job_id'] . "\n";
            }
        }

        echo "success";

    }
}
