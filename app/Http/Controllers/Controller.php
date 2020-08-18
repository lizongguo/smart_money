<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getNewCodeId($type = "company", $code="", $id = 0) {
        $new = "";
        $preg = "/(.*)-([\d]*)/u";
        switch ($type) {
            case "company_job" :
                preg_match($preg, $code, $arr);
                if ($code && $arr[1] && $arr[2]) {
                    $no = (int)$arr[2] + 1;
                    $new = $arr[1] . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);
                } else {
                    $new = 'C' . str_pad($id, 4, '0', STR_PAD_LEFT) . '-001';
                }

                break;
            case "agent_job" :
                preg_match($preg, $code, $arr);
                if ($code && $arr[1] && $arr[2]) {
                    $no = (int)$arr[2] + 1;
                    $new = $arr[1] . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);
                } else {
                    $new = 'A' . str_pad($id, 4, '0', STR_PAD_LEFT) . '-001';
                }

                break;
            case "company" :
                $new = 'C' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
            case "agent" :
                $new = 'A' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
            case "user" :
                $new = 'S' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
            case "job" :
                $new = 'J' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
        }

        return $new;
    }
}
