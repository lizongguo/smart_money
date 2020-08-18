<?php

namespace App\Jobs;

use App\Models\Experience;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WebmToMp4Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $experienceId = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($experienceId)
    {
        $this->experienceId = $experienceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        $experience = Experience::find($this->experienceId);
        if (!$experience || empty($experience->video_url) || !preg_match("/\.webm$/i", $experience->video_url)) {
            return false;
        }

        $public = public_path();
        $public = str_replace("\\", '/', $public);
        \Log::info($public);
        $input = $public . $experience->video_url;
        $saveOutput = $experience->video_url . '.mp4';
        $outPut = $public . $saveOutput;
//        ffmpeg -i input.webm -pass 2 -vf fps=fps=120 -f mp4 output.mp4
//        ffmpeg 转码
        try {
            $command = env('FFMPEG_PATH', '/usr/bin/ffmpeg/bin/ffmpeg') . " -i {$input} -vf fps=fps=120 -f mp4 {$outPut}";
            \Log::info($command);
            exec($command, $out, $returnStatus);

            if ($returnStatus === 0) {
                $experience->video_url = $saveOutput;
                $experience->save();
                \Log::info("WebmToMp4-success: experienceId={$this->experienceId}，origin-url={$experience->video_url}, out-url={$saveOutput}, result={$returnStatus}, out:" . var_export($out, true));
            } else {
                \Log::info("WebmToMp4-failure: experienceId={$this->experienceId}，origin-url={$experience->video_url}, out-url={$saveOutput}, result={$returnStatus}, out:" . var_export($out, true));
            }

        } catch (\Exception $exception){
            \Log::info("WebmToMp4-failure: experienceId={$this->experienceId}，exception:" . $exception->getMessage());
            return false;
        }

        return true;
    }
}
