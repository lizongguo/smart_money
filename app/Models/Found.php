<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Found extends BaseModel
{
    protected $table = 'found';
    protected $primaryKey = 'id';
    
    /**
     * 扩展列表查询
     * @param type $sh 查询条件
     * @param type $all 是否全部查询
     * @param type $limit 每页数
     * @param type $field 查询字段
     * @return type
     */
    public function getList($sh=[], $all = false, $limit = 20, $field = null)
    {
        $rs = parent::getList($sh, $all, $limit, $field);
        foreach($rs as &$item){
            $item->shops = $item->shops()->pluck('shop_name');
            $item->category = $item->category;
        }
        return $rs;
    }


    public function saveFound($data)
    {
        \DB::statement('truncate table found');
        \DB::beginTransaction();
        try {
            foreach ($data as $k => $v) {
                if($k==0) {
                    continue;
                }
                $insert = [
                    'found_no' => $v[0],
                    'current_name' => (string)$v[1],
                    'ever_name' => (string)$v[2],
                    'current_gp' => (string)$v[3]
                ];
                \DB::table('found')->insert($insert);
            }
            \DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
    }

    public function saveUser($data)
    {
        \DB::statement('truncate table user_info');
        \DB::beginTransaction();
        try {
            foreach ($data as $k => $v) {
                if($k==0) {
                    continue;
                }
                $insert = [
                    'email' => 100000+(int)$v[0],
                    'password' => '$2y$10$PMUg5QIrcyAmOMfDAOjYmuWpb/ougkM4mmFvX7tcymWrPTuLZxmL.',
                    'user_id' => $v[0],
                    'name' => (string)$v[1],
                    'email_info' => (string)$v[4],
                    'phone' => (string)$v[3],
                    'address' => (string)$v[5],
                    'tel_phone' => (string)$v[7],
                    'recipient' => (string)$v[6],
                    'note' => (string)$v[8],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                \DB::table('users')->insert($insert);
            }
            \DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
    }

    public function saveFroject($data)
    {
        \DB::statement('truncate table project');
        \DB::statement('truncate table found_project');
        \DB::beginTransaction();
        try {
            foreach ($data as $k => $v) {
                if($k==0) {
                    continue;
                }
                $project = [
                    'project_no' => $v[2],
                    'company_name' => (string)$v[3],
                    'address' => (string)$v[5],
                    'website' => (string)$v[6],
                    'state' => (string)$v[7],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                \DB::table('project')->insert($project);
                $found_project = [
                    'fund_id' => $v[0],
                    'project_id' => $v[2],
                    'rate' => (string)$v[4],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                \DB::table('found_project')->insert($found_project);
            }
            \DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
    }

    public function saveUserFound($data)
    {
        \DB::statement('truncate table user_found');
        \DB::beginTransaction();
        try {
            foreach ($data as $k => $v) {
                if($k==0) {
                    continue;
                }
                $insert = [
                    'user_id' => $v[0],
                    'fund_id' => $v[2],
                    'proportion' => $v[4],
                    'actual_pay' => $v[5] ? $v[5] : 0,
                    'subcribe_pay' => $v[6] ? $v[6] : 0,
                    'pay_interest' => $v[7] ? $v[7] : 0,
                    'interest_allowance' => $v[8] ? $v[8] : 0,
                    'add_pay' => $v[9] ? $v[9] : 0,
                    'pay_amount' => $v[10] ? $v[10] : 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                \DB::table('user_found')->insert($insert);
            }
            \DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
    }

    public function getFoundById($id)
    {
        $found = $this->select('*')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();
        if (!$found) {
            return false;
        }
        return $found;
    }

    public function saveFoundData($data) {
        \DB::beginTransaction();
        try {
            $id = $data['id'];
            $update = [
                'current_name' => $data['current_name'],
                'ever_name' => $data['ever_name'],
                'current_gp' => $data['current_gp'],
                'total_value_cn' => $data['total_value_cn'],
                'image' => $data['image'],
                'introduce' => $data['introduce'],
                'currency' => (int)$data['currency'],
            ];
            $this->where('id', $id)->update($update);
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return true;
    }


}
