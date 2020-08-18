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
namespace App\Services\Couchbase;

interface Couchbase
{
    
    public function getData();
    
    public function init();
    /**
     * 
     * @param array $law
     */
    public function saveToXml (array $law);
    
    public function linkXmlFile();

}
