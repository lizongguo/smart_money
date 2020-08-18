<?php

/**
 * BraveIosPush
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-26 10:50:14
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Libraries;

class BraveIosPush {
    const SERVER_TYPE = 1; /**< @type integer server type. */
	const PUSH_TYPE = 2; /**< @type integer push type. */

    protected $server = null;
    protected $push = null;
    protected $config = [];
    protected $messageNum = 0;

    public function __construct() {
        $config = config('push');
        $env = $config['push_env'] == '1' ? 'staging' : 'production';
        $this->config = [
            'push_env' => $config['push_env'],
            'provider_cert_pass' => $config['provider_cert_pass'],
            'push_write_interval' => $config['push_write_interval'],
            'root_cert' => $config['push_cert_path'] . DIRECTORY_SEPARATOR . 'entrust_root_certification_authority.pem',
            'provider_cert' => $config['push_cert_path'] . DIRECTORY_SEPARATOR . $env . '_cert.pem',
        ];
    }
    
    public function getServer() {
        if ($this->server) {
            return $this->server;
        }
        $this->server = new \ApnsPHP_Push_Server(
            $this->config['push_env'],
            $this->config['provider_cert']
        );
        
        // Set the Root Certificate Autority to verify the Apple remote peer
        $this->server->setRootCertificationAuthority($this->config['root_cert']);

        // Set the number of concurrent processes
        $this->server->setProcesses(2);

        // Starts the server forking the new processes
        $this->server->start();
        return $this->server;
    }
    
    public function getPush() {
        if ($this->push) {
            return $this->push;
        }
        // Instantiate a new ApnsPHP_Push object
        $push = new \ApnsPHP_Push(
            $this->config['push_env'],
            $this->config['provider_cert']
        );

        // Set the Provider Certificate passphrase
        if (!empty($this->config['provider_cert_pass'])) {
            $push->setProviderCertificatePassphrase($this->config['provider_cert_pass']);
        }

        // Set the Root Certificate Autority to verify the Apple remote peer
        $push->setRootCertificationAuthority($this->config['root_cert']);

        // Connect to the Apple Push Notification Service
        $push->connect();
        
        if (!empty($this->config['push_write_interval']) && $this->config['push_write_interval'] >= 0) {
            $push->setWriteInterval($this->config['push_write_interval'] * 1000);
        }
        $this->push = $push;
        
        return $this->push;
    }
    
    /**
     * 
     * @param int $type
     * @param string $deviceToken
     * @param string $content
     * @param array $customPropertys
     * @return boolean
     */
    protected function addMessage(int $type, $deviceToken, string $content, array $customPropertys = []) {
        if(is_array($deviceToken) && count($deviceToken) < 1 || empty($deviceToken) 
            || empty($content) || $type != self::PUSH_TYPE && $type != self::SERVER_TYPE) {
            return false;
        }
        
        // Instantiate a new Message with a single recipient
        $message = new \ApnsPHP_Message();
        
        if(is_array($deviceToken)) {
            foreach($deviceToken as $token) {
                $message->addRecipient($token);
            }
        } else {
            $message->addRecipient($deviceToken);
        }
        // set message id
        $this->messageNum++;
        $message->setCustomIdentifier(sprintf("Message-Badge-%03d", $this->messageNum));
        
        // Set a simple welcome text
        $message->setText($content);

        // Play the default sound
        $message->setSound();

        // Set a custom property
        if(is_array($customPropertys)) {
            foreach ($customPropertys as $key => $custom) {
                $message->setCustomProperty($key, $custom);
            }
        }
        
        if($type == self::PUSH_TYPE){
            $this->getPush()->add($message);
        } else {
            $this->getServer()->add($message);
        }
        return true;
    }
    
    /**
     * 
     * @param string $deviceToken
     * @param string $content
     * @param array $customPropertys
     */
    public function addPushMessage($deviceToken, string $content, array $customPropertys = []) {
        $this->addMessage(self::PUSH_TYPE, $deviceToken, $content, $customPropertys);
        $this->push->send();
    }
    
    /**
     * 
     * @param string $deviceToken
     * @param string $content
     * @param array $customPropertys
     * @return type
     */
    public function addServerMessage($deviceToken, string $content, array $customPropertys = []) {
        return $this->addMessage(self::SERVER_TYPE, $deviceToken, $content, $customPropertys);
    }
}
