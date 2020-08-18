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
use App\Repositories\Solr\SolrInterface;
use Log;
use DB;

class SolrSearchService implements SolrSearch
{
    protected $solr = null;
    protected $fields = ['id', 'koufubi', 'shikoubi', 'docnamerireki', 'shikoubihyouki', 'hourei_cd', 'hourei_name', 'nextid', 'misekouflag', 'hatsurei'];
    protected $sort = null;
    protected $sortType = 'asc';

    public function __construct(SolrInterface $solr)
    {
        $this->solr = $solr;
    }
    
    /**
     * 
     * @param string $keyword
     * @param type $conn
     * @param type $hourei_name
     * @param int $status
     * @param int $page
     * @param int $limit
     * @return array ['total' => 0, 'list' => []]
     */
    public function search(string $keyword = "", string $conn = 'AND', string $hourei_name = '', int $status = 0, int $page = 1, int $limit = 10)
    {
//        $ping = $this->solr->ping();
//        if(strtoupper($ping['status']) !== 'OK') {
//            throw new \Solarium\Exception\HttpException('solr ping is not accessible.');
//        }
        
        $conn = strtoupper($conn);
        $queries = [];
        //search freewords
        $keyword = strtr($keyword, array("　" => ' '));
        $keywords = preg_split("/[\s]+/isU", $keyword, 0, PREG_SPLIT_NO_EMPTY);
        if(count($keywords)) {
            $freeword = array();
            foreach ($keywords as $word_arr) {
                $freeword[] = '("' . $word_arr . '")';
            }
            $conn = ' ' . $conn . ' ';
            $queries[] = '_text_:(' . implode($conn, $freeword) . ')';
        }
        //search hourei name
        if (!empty($hourei_name)) {
            $queries[] = 'docnamerireki:("' . $hourei_name . '")';
        }
        $fq = '';
        //search 未施行 flag
        if ($status < 1) {
            $fq = 'misekouflag:0';
        }
        
        $query = "*:*";
        if (count($queries)) {
            $query = implode(' AND ', $queries);
        }
        
        $offset = ($page - 1) * $limit;
        try {
            $result = $this->solr->search($query, $this->fields, $fq, $offset, $limit, $this->sort, $this->sortType);
        }  catch (\Exception $e) {
            Log::error("SolrSearchError: " . $e->getMessage());
            throw new \Solarium\Exception\HttpException('solr search is failure.');
        }
        $return = $this->parseSolrResult($result);
        return $return;
    }
    
    public function parseSolrResult($result) {
        $return = ['total' => 0, 'list' => []];
        $return['total'] = $result->getNumFound();
        foreach ($result as $document) {
            $item = [
                'docid' => '',
                'koufubi' => '',
                'shikoubi' => '',
                'docnamerireki' => '',
                'shikoubihyouki' => '',
                'hourei_cd' => '',
                'hourei_name' => '',
                'nextid' => '',
                'misekouflag' => '0',
                'hatsurei' => ''
            ]; 
            foreach ($document as $field => $value) {
                if(!isset($item[$field]) && $field != 'id') {
                    continue;
                }
                if (is_array($value)) {
                    $value = $value[0];
                }
                if($field == 'id') {
                    $field = 'docid';
                }
                $item[$field] = $value;
            }
            $return['list'][] = $item;
        }
        return $return;
    }
    
    public function saveKeywordToDb(string $keyword, int $user_id) {
        $now = date('Y-m-d H:i:s');
        $result = DB::table('tb_search_keywords')->insert(['keywords' => $keyword, 'user_id' => $user_id, 'created_at' => $now, 'updated_at' => $now]);
        return $result === false ? false : true;
    }
}
