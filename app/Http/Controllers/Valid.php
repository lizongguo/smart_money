<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use DB;

class Valid extends BaseController
{
    var $error = null;
    var $data = null;


    function getError($field = null) {
        if (is_null($field)) {
            return $this->error;
        }
        else {
            return $this->getValue($this->error, $field);
        }
    }

    function setError($field, $error) {
        $this->error[$field] = $error;
    }

    function unsetError($field = null) {
        if (is_null($field)) {
            $this->error = null;
        }
        else {
            $this->unsetValue($this->error, $field);
        }
    }

    function valid($config, $data, $validAll = true) {
        $this->data = $data;
		$this->error = array();
        if (empty($config)) {
            return true;
        }
        
        foreach ($config as $field => $rules) {
            if (!is_array($rules)) {
                continue;
            }
            
            // regex
            if (preg_match('/^\/\^(.*?)\$\/([is]*)$/i', $field)) {
                foreach ($this->data as $k => $v) {
                    if (preg_match($field, $k)) {
                        $this->handle($k, $rules);
                    }
                }
                
                continue;
            }
            // normal
            if (!isset($this->data[$field])) {
                $this->data[$field] = null;
            }
            $this->handle($field, $rules);
            
            // check
            if (!$validAll && $this->error) {
                return false;
            }
        }
        
        return $this->error? false: true;
    }
    
    function handle($field, $rules) {
        if (empty($rules)) {
            return false;
        }
        foreach ($rules as $v) {
            // func & msg
            if (count($v) < 2) {
                continue;
            }

            $func = $v[0];
            $error = $v[1];

            // vars
            $vars = null;

            if (isset($v[2]))
                $vars = $v[2];

            if (method_exists($this, $func)) {
                if (!$this->$func($field, $vars)) {
                    $this->setError($field, $error);
                    break;
                }
            }
        }
        return true;
    }

    function isNotNull($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data))
            return empty($data)? false: true;
        else
            return strlen($data)? true: false;
    }

    function isNumber($field, $vars) {
        $regex = '/^[0-9]+$/';
        return preg_match($regex, $this->data[$field]);
    }
    
	function isMail($field, $vars) {
        $regex = "/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\._\-]*\.[a-zA-Z0-9\._\-]*$/is";
        return preg_match($regex, $this->data[$field]);
    }

    function isNumeric($field, $vars) {
        $regex = '/^[1-9]+[0-9]*$/';
        return preg_match($regex, $this->data[$field]);
    }
    
    function isFloat($field, $vars) {
        if(!is_numeric($this->data[$field]))
        {
            return false;
        }
        $regex = '/^\d+\.?\d*$/i';
        return preg_match($regex, $this->data[$field]);
    }

    /**
     * @param $field
     * @param $vars
     * @return int
     * 修改邮箱验证规则  6位纯数字或者  7位带-
     */
    function isPostCode($field, $vars) {
        $regex = "/^[0-9]{3}\-[0-9]{4}$/";
        $regex1 = "/^[0-9]{6}$/";
        $result = preg_match($regex, $this->data[$field]);
        $result1 = preg_match($regex1, $this->data[$field]);
        if($result == 1  || $result1 == 1){
            return 1;
        }else{
            return 0;
        }
//        return preg_match($regex, $this->data[$field]);
    }

    function isTelCode($field, $vars) {
    	$regex = "/^[0-9]{2,4}\-[0-9]{2,4}\-[0-9]{2,4}$/";
    	return preg_match($regex, $this->data[$field]);
    }
    
    function isCardNo($field, $vars) {
    	$regex = "/^[0-9]{4}\-[0-9]{4}\-[0-9]{4}\-[0-9]{4}$/";
    	return preg_match($regex, $this->data[$field]);
    }

    function isPassword($field, $vars) {
        $regex = '/^[0-9a-z]{6,12}$/';
        return preg_match($regex, $this->data[$field]);
    }

    function isJaPhone($field, $vars) {
        $regex = '/^[0-9]{8,11}$/';
        return preg_match($regex, $this->data[$field]);
    }

    function isPhone($field, $vars) {
        $regex = "/^(1)\\d{10}$/";
        return preg_match($regex, $this->data[$field]);
    }
    function isWechat($field, $vars) {
        $regex = "/^[0-9a-zA-Z_]+$/";
        return preg_match($regex, $this->data[$field]);
    }

    function isTel($field, $vars){
        $regex = "/^(0?1[358]\d{9})|((0(10|2[1-3]|[3-9]\d{2}))?[1-9]\d{6,7})$/";
        return preg_match($regex, $this->data[$field]);
    }

    function isIdCard($field, $vars){
        $regex = "/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/";
        return preg_match($regex, $this->data[$field]);
    }

    function isValidExt($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data) && isset($data['error_msg']) && $data['error_msg'] == 'file_ext_invalid') {
            return false;
        }

        return true;
    }

    function isValidSize($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data) && isset($data['error_msg']) && $data['error_msg'] == 'file_size_invalid') {
            return false;
        }

        return true;
    }
    
    function isUploadFile($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data) && isset($data['error_msg']) && $data['error_msg'] == 'file_is_not_upload') {
            return false;
        }
        return true;
    }
    
    function isUploadFileFail($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data) && isset($data['error_msg']) && $data['error_msg'] == 'file_tmp_null') {
            return false;
        }
        return true;
    }

    function fileMoveSucc($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data) && isset($data['error_msg']) && $data['error_msg'] == 'file_move_failure') {
            return false;
        }

        return true;
    }
    
    function imageValidPixel($field, $vars) {
        $data = $this->data[$field];
        if (is_array($data) && isset($data['error_msg']) && $data['error_msg'] == 'image_pixel_failure') {
            return false;
        }
        return true;
    }

    function isAccountNameExists($field, $vars) {
    	$id   = (int)$vars['id'];
    	$name = $this->data[$field];
    	$sql = "select * from tb_admin where id != {$id} and admin_name = '{$name}' and deleted = 0 ";
    	$row = DB::select($sql);
    	return $row ? false : true;
    }

    function isAccountMailExists($field, $vars) {
    	$id   = (int)$vars['id'];
    	$name = $this->data[$field];
    	$sql = "select * from tb_admin where id != {$id} and email = '{$name}' and deleted = 0 ";
    	$row = DB::select($sql);
    	return $row ? false : true;
    }

    function isRightNameExists($field,$vars) {
    	$id   = (int)$vars['id'];
    	$name = $this->data[$field];
    	$sql = "select * from tb_right where id != {$id} and `name` = '{$name}' and deleted = 0 ";
    	$row = DB::select($sql);
    	return $row ? false : true;
    }

    function isAccountExists($field,$vars) {

        $id   = (int)$vars['occupation_id'];
        $name = $this->data[$field];
        $sql = "select * from tb_occupation where occupation_id != {$id} and `occupation_name` = '{$name}' and deleted = 0 ";
        $row = DB::select($sql);
        return $row ? false : true;
    }

    function langs($data) {
    	$lang = config('lang');
    	foreach ($data as $k => $v) {
    		if(isset($lang[$v]))
    		{
    			$data[$k] = $lang[$v];
    		} else {
    			$data[$k] = '字段错误';
    		}
    	}
    	return $data;
    }

}
