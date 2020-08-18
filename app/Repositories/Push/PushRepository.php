<?php

/**
 * PushRepository
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-27 14:57:47
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Repositories\Push;
use App\Libraries\BraveIosPush;
use App\Repositories\Notices\NoticesInterface;
use DB;
use Log;

class PushRepository 
{
    protected $pushObj = null;
    protected $pushServer = null;
    protected $notice = null;
    protected $type = 2;
    protected $actionList = [
        '1' => 'Server',
        '2' => 'Push',
    ];

    /**
     * 
     * @param BraveIosPush $iosPush
     * @param NoticesInterface $notice
     */
    public function __construct(BraveIosPush $iosPush, NoticesInterface $notice) {
        $this->notice = $notice;
        $this->pushObj = $iosPush;
    }
    
    /**
     * 
     * @param type $type
     * @return boolean
     */
    public function sendPush($type = 2) {
        if(!in_array($type, [1, 2])) {
            return false;
        }
        $this->type = $type;
        $action = "get{$this->actionList[$this->type]}";
        $this->pushServer = $this->pushObj->$action();
        $limit = 20;
        $count = $limit;
        while ($count >= $limit){
            $noticeList = $this->notice->getPushNoticeLish($limit);
            foreach ($noticeList as $notice) {
                $userList = $this->notice->getPushNoticeUserLish($notice['notice_id']);
                DB::beginTransaction();
                //send push
                $this->addMessage($notice, $userList);
                
                $errors = $this->pushServer->getErrors();
                //update notice push status
                if (!empty($errors) || $this->notice->setNoticePushStatus($notice['notice_id'], 1) === false
                    || $this->notice->setNoticeUserSendStatus($notice['notice_id'], 1) === false)
                {
                    if(!empty($errors)) {
                        Log::info($errors, []);
                    }
                    DB::rollBack();
                    continue;
                }
                DB::commit();
            }
            $count = count($noticeList);
        }
        if($this->type == 2) {
            //push
            $this->pushServer->disconnect();
        } else {
            //server 
            do {
                usleep(10000000);
                $queues = $this->pushServer->getQueue(false);
            }while(count($queues) > 0);
        }
        return true;
    }
    
    protected function addMessage($notice, $userList) {
        $deviceToken = [];
        foreach ($userList as $user) {
            $user = (array) $user;
            if($user['device_token']) {
                $deviceToken[] = $user['device_token'];
            }
        }
        if(count($deviceToken) > 0) {
            $action = "add{$this->actionList[$this->type]}Message";
            $this->pushObj->$action($deviceToken, $notice['message'], ['hourei_cd' => $notice['hourei_cd']]);
        }
        return true;
    }
}
