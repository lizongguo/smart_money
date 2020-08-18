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
namespace App\Services\Search;

interface SolrSearch
{
    public function search(string $keyword, string $conn, string $hourei_name, int $status, int $page, int $limit);
    public function parseSolrResult($result);
    public function saveKeywordToDb(string $keyword, int $user_id);
}
