<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Controllers\Traits\BaseModelTrait;
use Illuminate\Support\Facades\Hash;

class Company extends BaseModel
{
    use BaseModelTrait {
        BaseModelTrait::saveItem as saveParentItem;
    }

    protected $table = 'company';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

    public function saveItem($data) {
        if (!empty($data['password'])) {
            $data['password'] = \Hash::make($data['password']);
        }else{
            unset($data['password']);
        }

        $id = $this->saveParentItem($data);

        $update = [
            'id' => $id,
            'account_code' => $this->getNewCodeId('company', '', $id),
        ];

        $this->saveParentItem($update);

        return $id;
    }

    //通过Email获取用户
    function getItemByEmail($email) {
        $rs = $this->where('email', $email)->where('deleted', 0)->first();
        return $rs;
    }

    public function changePassword($user_id, $opassword, $password, &$error){
        $user = $this->where('id', (int)$user_id)->where('deleted', 0)->first();
        if(!$user) {
            $error['status'] = '410';
            $error['msg'] = config('code.alert_msg.account.user_not_exist');
            return false;
        }

        if(!Hash::check($opassword, $user->password)) {
            $error['status'] = '410';
            $error['msg'] = config('code.alert_msg.account.password_error');
            return false;
        }

        $rs = $this->where('id', $user_id)->update(['password' => Hash::make($password)]);
        if($rs === false) {
            $error['status'] = '500';
            $error['msg'] = config('code.alert_msg.system.error');
            return false;
        }
        return true;
    }

    public function changeEmail($user_id, $opassword, $email, &$error){
        $user = $this->where('id', (int)$user_id)->where('deleted', 0)->first();
        if(!$user) {
            $error['status'] = '410';
            $error['msg'] = config('code.alert_msg.account.user_not_exist');
            return false;
        }

        if ($email == $user->email) {
            $error['status'] = '410';
            $error['msg'] = config('code.alert_msg.account.mail_identical');
            return false;
        }

        $mailInfo = $this->where('id', "!=", (int)$user_id)->where('email', $email)->where('deleted', 0)->first();
        if($mailInfo) {
            $error['status'] = '410';
            $error['msg'] = config('code.alert_msg.account.mail_exist');
            return false;
        }

        if(!Hash::check($opassword, $user->password)) {
            $error['status'] = '410';
            $error['msg'] = config('code.alert_msg.account.password_error');
            return false;
        }

        $rs = $this->where('id', $user_id)->update(['email' => $email]);
        if($rs === false) {
            $error['status'] = '500';
            $error['msg'] = config('code.alert_msg.system.error');
            return false;
        }
        return true;
    }

}
