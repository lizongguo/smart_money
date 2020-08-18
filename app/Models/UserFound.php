<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Redis;
use App\Repositories\Wechat\SmallProgramApiRepository;
use App\Services\Aliyun\AlipayService;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserFound extends Authenticatable
{
    protected $table = 'user_found';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

    function getUserFound($user_id)
    {
        $obj = $this->select('found.*',
            'user_found.proportion',
            'user_found.actual_pay',
            'user_found.subcribe_pay',
            'user_found.pay_interest',
            'user_found.interest_allowance',
            'user_found.add_pay',
            'user_found.pay_amount'
        );
        $obj->join('found', 'user_found.fund_id', '=', 'found.found_no');
        $obj->where('found.deleted', 0);
        $obj->where('user_found.user_id', '=', $user_id);
        $data = $obj->get()->toArray();
        return $data;
    }

    function getAllFound()
    {
        $obj = DB::table('found')->select('found.*');
        $obj->where('found.deleted', 0);
        $data = $obj->get()->toArray();
        return $data;
    }

    function getProject($id)
    {
        $infos = DB::table('project')
                ->select('project.*', 'found_project.rate', 'found_project.amount_cn', 'found_project.amount_us')
                ->join('found_project', 'found_project.project_id', '=', 'project.project_no')
                ->where('project.deleted', '0')
                ->where('found_project.fund_id', $id)
                ->get()
                ->toArray();
        return $infos;
    }

    function getProjectDetail($id)
    {
        $info = DB::table('project')
                ->select('project.*')
                ->where('project.deleted', '0')
                ->where('project.project_no', $id)
                ->first();
        return $info;
    }

    function getFoundById($user_id, $found_no)
    {
        $obj = $this->select('found.*',
            'user_found.proportion',
            'user_found.actual_pay',
            'user_found.subcribe_pay',
            'user_found.pay_interest',
            'user_found.interest_allowance',
            'user_found.add_pay',
            'user_found.pay_amount'
        );
        $obj->join('found', 'user_found.fund_id', '=', 'found.found_no');
        $obj->where('found.deleted', 0);
        $obj->where('user_found.user_id', '=', $user_id);
        $obj->where('found.found_no', '=', $found_no);
        $data = $obj->first()->toArray();
        return $data;
    }

    function getFoundDetail($id)
    {
        $info = DB::table('found')
                ->select('found.*')
                ->where('found.found_no', $id)
                ->first();
        return $info;
    }

    function getfinancial($id, $type)
    {
        switch ($type) {
            case 1:
                $table = 'found_financial';
                break;
            case 2:
                $table = 'found_audit';
                break;
            case 3:
                $table = 'found_risk';
                break;
            case 4:
                $table = 'found_other';
                break;
            case 5:
                $table = 'project_risk';
                break;
            default:
                break;
        }
        $rs = DB::table($table)
                ->select('*')
                ->where('deleted', '0')
                ->where('fund_id', $id)
                ->get()
                ->toArray();
        return $rs;
    }

    function getFileDetail($id, $type)
    {
        switch ($type) {
            case 1:
                $table = 'found_financial';
                break;
            case 2:
                $table = 'found_audit';
                break;
            case 3:
                $table = 'found_risk';
                break;
            case 4:
                $table = 'found_other';
                break;
            case 5:
                $table = 'project_risk';
                break;
            case 6:
                $table = 'project';
                break;
            default:
                break;
        }
        if ($type==6) {
            $info = DB::table($table)
                ->select('file_path as path','company_name as name')
                ->where('id', $id)
                ->first();
        } else {
            $info = DB::table($table)
                ->select('*')
                ->where('id', $id)
                ->first();
        }
        return $info;
    }

    function getCapital($id)
    {
        $items = DB::table('found_capital')
                ->select('found_capital.*', 'users.name as uname')
                ->join('users', 'users.user_id', '=', 'found_capital.user_id')
                ->where('found_capital.deleted', '0')
                ->where('found_capital.fund_id', $id)
                ->get()
                ->toArray();
        return $items;
    }

}
