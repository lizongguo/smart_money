<?php

/**
 * GetCouchbaseRepository
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-3-27 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Repositories\Couchbase;

class CouchbaseRepository implements CouchbaseInterface
{
    /*
     * couchbase auth
     */
    protected $authenticator;
    protected $cluster;
    protected $bucket;


    public function __construct() {
        
    }
    
    /**
     * couchbase init
     */
    public function init() {
        $this->cluster = new \Couchbase\Cluster("couchbase://" . config('couchbase.couchbase_host') . ":" . config('couchbase.couchbase_port'));
        //Authentication
        if (config('couchbase.couchbase_auth_flag')) {
            $this->authenticator = new \Couchbase\PasswordAuthenticator();
            $this->authenticator->username(config('couchbase.couchbase_auth_name'))->password(config('couchbase.couchbase_auth_pw'));
            $this->cluster->authenticate($this->authenticator);
        }
        $this->bucket = $this->cluster->openBucket(config('couchbase.couchbase_bucket'));
    }
    
    /**
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getDataList(int $page = 1, int $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $query = \Couchbase\N1qlQuery::fromString(
            "SELECT meta().id FROM `mobilekokuho` where filesyubetsu='JB' offset {$offset} limit {$limit}"
        );
        $query->consistency(\Couchbase\N1qlQuery::REQUEST_PLUS);
        $query->crossBucket(true);
        $result = $this->bucket->query($query, true);
        return $result ? $result : false;
    }
    
    public function getDataByDocid(string $docid)
    {
        $query = \Couchbase\N1qlQuery::fromString(
            "SELECT meta().id, * FROM `mobilekokuho` WHERE meta().id='{$docid}';"
        );
        $query->consistency(\Couchbase\N1qlQuery::REQUEST_PLUS);
        $query->crossBucket(true);
        $result = $this->bucket->query($query, true);
        return $result ? $result : false;
    }

}
