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
use Solarium\Client AS SolrClient;

class SolrRepository implements SolrInterface
{
    protected $solrClient = null;
    protected $solrQuery = null;

    public function __construct()
    {
        $options = config('solr');
        $this->solrClient = new SolrClient($options);
        $this->solrQuery = $this->solrClient->createSelect();
    }
    
    public function search(string $query = "*.*", array $fields = [], string $fq = '', int $offset = 0, int $limit = 10, $sort = null, $sort_type = 'asc')
    {
        $this->solrQuery->setQuery($query);
        $this->solrQuery->setStart($offset)->setRows($limit);
        if ($sort) {
            $this->solrQuery->addSort($sort, $sort_type);
        }
        if (count($fields) > 0) {
            $this->solrQuery->setFields($fields);
        }
        if ($fq) {
            $this->solrQuery->createFilterQuery('fq')->setQuery($fq);
        }
        
        return $this->solrClient->execute($this->solrQuery);
        
    }
    
    public function ping()
    {
        $rs = $this->solrClient->ping($this->solrClient->createPing());
        return $rs->getData();
    }
}
