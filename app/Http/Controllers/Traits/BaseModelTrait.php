<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use DB;
/**
 * AdminManagerTrait
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2019-3-4 15:55:34
 * @copyright   Copyright(C) kbftech Inc.
 */
trait BaseModelTrait {
    protected $field = [];
    protected $braveFields = null;

    public function __construct() {
        parent::__construct();
        $this->braveFields = app()->make('App\Libraries\BraveFields');
        $this->field = $this->braveFields->getFieldByTable($this->table);
    }
    
    public function getFields() {
        return $this->field;
    }
    
    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    public function getList($sh=[], $all = false, $limit = 20, $field = null) {
        $obj = $this->whereExtend($sh);
        if (isset($sh['order']) && $sh['order']) {
            $obj = $obj->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        } else {
//            $obj = $obj->orderBy($this->table . '.' .$this->primaryKey, 'desc');
        }
        
        if (!empty($field) && is_array($field)) {
            call_user_func_array(array($obj, 'select'), $field);
        }
        
        if (!$all) {
            $rs = $obj->paginate($limit);
        } else {
            $rs = $obj->get();
        }
        return $rs;
    }
    
    public function whereExtend($sh = [], $obj = null) {
        if (empty($obj)) {
            $obj = $this;
        }
        //排除有些表没有deleted字段
        if (isset($this->isDeleted) && $this->isDeleted === true) {
            $obj = $obj->where($this->table .'.deleted', 0);
        }else {
            $obj = $obj->where(\DB::raw('1'), 1);
        }
        $this->parseSh($obj, $sh);
        return $obj;
    }
    
    /**
     * 检索构造器
     * @param type $obj
     * @param type $sh
     */
    protected function parseSh(&$obj, $sh) {
        if(isset($sh['order'])) {
            unset($sh['order']);
        }
        foreach($sh as $key => $val) {
            if(is_array($val)) {
                switch ($val['conn']) {
                    case 'in':
                        $obj->whereIn($key, $val['value']);
                        break;
                    case 'notin':
                        $obj->whereNotIn($key, $val['value']);
                        break;
                    case 'orlike':
                        $field = $val['filed'];
                        $value = $val['value'];
                        $obj = $obj->where(
                            function ($query) use ($field, $value) {
                                foreach ($field as $v) {
                                    $query->orWhere("{$v}", 'like', "%{$value}%");
                                }
                            }
                        );
                        break;
                    case 'lk':
                        $obj->where($key, 'like', "%{$val['value']}%");
                        break;
                    case 'llk':
                        $obj->where($key, 'like', "{$val['value']}%");
                        break;
                    case 'rlk':
                        $obj->where($key, 'like', "%{$val['value']}");
                        break;
                    case 'between':
                        $obj->whereBetween($key, $val['value']);
                        break;
                    default :
                        $obj->where($key, $val['conn'], $val['value']);
                        break;
                }
            } else if (!preg_match('#^\s*$#is', $val)) {
                $obj->where($key, $val);
            }
        }
    }
    
    /**
     * 数据保存 过滤掉不存在的字段
     * @param type $data
     * @return boolean
     */
    public function saveItem($data) {
        if ($this->field) {
            $newData = [];
            if (isset($data[$this->primaryKey]) && empty($data[$this->primaryKey])) {
                unset($data[$this->primaryKey]);
            }
            
            foreach ($this->field as $v) {
                if ( isset($data[$v]) ) {
                    $this->{$v} = $data[$v];
                    $newData[$v] = $data[$v];
                }
            }
            if (empty($newData[$this->primaryKey])) {
                $result = $this->save();
                $id = ($result === false) ? false : $this->{$this->primaryKey};
            } else {
                $result = $this->where($this->primaryKey, $newData[$this->primaryKey])->update($newData);
                $id = ($result === false) ? false : $newData[$this->primaryKey];
            }

            return $id;
        }
        return false;
    }
    
    /**
     * 保存 非自增主键 的数据
     * @param type $data
     * @return boolean
     */
    public function saveUnAutoItem($data) {
        if ($this->field) {
            $newData = [];
            foreach ($this->field as $v) {
                if (isset($data[$v])) {
                    $this->{$v} = $data[$v];
                    $newData[$v] = $data[$v];
                }
            }
            if(isset($newData[$this->primaryKey]) && $item = $this->find($newData[$this->primaryKey])) {
                $result = $this->where($this->primaryKey, $newData[$this->primaryKey])->update($newData);
                $id = ($result === false) ? false : $newData[$this->primaryKey];
            }else {
                $result = $this->save();
                $id = ($result === false) ? false : $this->{$this->primaryKey};
            }
            
            return $id;
        }
        return false;
    }

    public function insertItems(array $data)
    {
        return $this->insert($data);
    }
    
    /**
     * 单语句批量插入数据
     * @param type $data
     * @param type $replace
     * @param type $is_ignore
     * @return boolean
     */
    public function insertBatch($data, $replace = false, $is_ignore = false) {
        if (count($data) < 1) {
            return true;
        }
        $ignore = '';
        if ($is_ignore) {
            $ignore = 'IGNORE';
        }
        $sql = ($replace ? "REPLACE INTO " : "INSERT {$ignore} INTO ") . " {$this->table} ";
        $i = 0;
        foreach ($data as $value) {
            $i++;
            if ($i == 1) {
                $sql .= "(`" . implode("`,`", array_keys($value)) . "`) VALUES";
            }
            $sql .= ($i == 1 ? "" : ",") . "('" . implode("','", $value) . "')";
        }
        $rs = DB::insert($sql);
        return $rs;
    }
    
    
    /**
     * 
     * @param array $data
     * @param 二维 array $option  sample [['oblect' => 'news', 'filed' => 'attachment']]
     * @return boolean
     */
    public function saveDataAttachFile($data, $option=[]) {
        
        DB::beginTransaction();
        try {
            $id = $this->saveItem($data);
            foreach ($option as $objectConfig) {
                $oldIds = DB::table('tb_attachment')
                    ->where('object', $objectConfig['object'])
                    ->where('deleted', 0)
                    ->where('object_id', $id)
                    ->pluck('id')->toArray();
                if(count($oldIds) < 1) {
                    $oldIds = [];
                }
                //判断老数据中已经删除的数据
                $reducesIds = array_diff($oldIds, $data[$objectConfig['filed']]);
                if(count($reducesIds) > 0) {
                    DB::table('tb_attachment')->whereIn('id', $reducesIds)->update(['deleted' => 1]);
                }
                
                //与新加附件做绑定
                $incsreaseIds = array_diff($data[$objectConfig['filed']], $oldIds);
                if(count($incsreaseIds) > 0) {
                    DB::table('tb_attachment')
                        ->whereIn('id', $incsreaseIds)
                        ->where('object', $objectConfig['object'])
                        ->update(['object_id' => $id]);
                }
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            Log::error($ex);
            return false;
        }
        return $id;
    }
    

    /**
     * 验证验证码
     * @param type $phone
     * @param type $code
     * @param type $type  register|forgetpwd|change
     * @return boolean
     */
    public function checkCode($phone, $code, $type = 'register') {
        $invalidMinute = config('code.invalid_minute');
        $redisKey = config("rediskeys.verification_{$type}_hash");
        if (!Redis::hexists($redisKey, $phone)) {
            return false;
        }
        $dataStr = Redis::hget($redisKey, $phone);
        $data = json_decode($dataStr, true);
        if (($data['time'] + $invalidMinute * 60) < time()) {
            Redis::hdel($redisKey, $phone);
            return false;
        }
        if($code != $data['code']) {
            return false;
        }
        return true;
    }
    
    /**
     * 删除redis中短信验证码。
     * @param type $phone
     * @param type $type
     */
    public function delRedisCode($phone, $type)
    {
        $redisKey = config("rediskeys.verification_{$type}_hash");
        Redis::hdel($redisKey, $phone);
    }
    
    /**
     * 通过id 获取指定数据
     * @param type $id
     * @return array $result
     */
    public function getOne($id) {
        $obj = $this->where('id', $id);
        if ($this->isDeleted == true) {
            $obj->where($this->table . '.deleted', 0);
        }
        $result = $obj->first();
        return $result;
    }
    
    /**
     * 按一定标砖生成 订单号
     * @return string
     */
    public function randomOrderSN($pre = '') {
        $preCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $year = date('Y');
        $index = ($year - 2018)%26;
        $num = intval(($year - 2018) / 26);
        $orderSn  = $pre . $preCode[$index] . $num . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }
    
    /**
     * 指定用户发送通知
     * @param type $user
     * @param type $content
     */
    public function sendPush($user, $content)
    {
        $jpush = app()->make('App\Services\Jpush\Jpush');
        $noticeModel = app()->make('App\Http\Models\UserNotice');
        if ( isset($user) && $user['system_switch'] == 1 && !empty($user['device_token'])) {
            $jpush->pushMemberMessage($user['id'], $content);
            $jpush->pushMemberMessage($user['id'], $content, 'message');
        }
        //添加系统notice
        $noticeModel->addNotice($user['id'], $content);
    }

    public function addHit($id) {
        DB::table($this->table)->where('id', $id)->increment('hits');
    }
    
    /**
     * 获取指定长度的随机字符
     * @param type $num
     */
    public function getRandomStr($num = 5) {
        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $strs = str_repeat($strs, 5);
        return substr(str_shuffle($strs), mt_rand(0, strlen($strs) - $num + 1), $num);
    }
    
    public function curlGetContents($url, $dest = null, $logContent = true) {
		if (empty($url)) {
			return false;
		}
		$log = "URL: {$url}\n";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		if ($dest) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
			curl_setopt($ch, CURLOPT_FILE, $dest);
		}
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->handleError('CURL_ERROR', $url, curl_error($ch));
			curl_close($ch);
			return false;
		}
		$info = curl_getinfo($ch);
		if ($info['http_code'] != '200') {
			$this->handleError($info['http_code'], $url, $result);
			return false;
		}
		if ($logContent) {
			$log .= 'CONTENT LENGTH:' . strlen($result) . "\n";
		}
		Log::info($log);
        
		return $result;
	}
    
    function handleError($errorCode, $url = null, $response = null){
        $data = array(
            'error' => $errorCode,
            'url' => $url,
            'response' => $response,
            'time' => date('Y-m-d H:i:s'),
        );
        Log::error(var_export($data, true));
    }
    
    public function deletedItem($ids){
        if (is_array($ids)) {
            $values = ['conn' => 'in', 'value' => $ids];
        }else if (!empty($ids)){
            $values = $ids;
        }else {
            return false;
        }
        $item = [$this->getPrimaryKey() => $values];
        
        if(isset($this->isDeleted) && $this->isDeleted == true) {
            $rs = $this->whereExtend($item)->update(['deleted' => 1]);
        } else {
            $rs = $this->whereExtend($item)->delete();
        }
        return $rs;
    }
}
