<?php

/**
 * GetCouchbaseRepository
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-16 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Repositories\Solr;

interface SolrInterface
{
    public function search(string $query, array $fields, string $fq, int $offset, int $limit, $sort, $sort_type);
    public function ping();
}
