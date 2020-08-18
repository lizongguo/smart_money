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
use App\Libraries\BraveXml;
use App\Repositories\Couchbase\CouchbaseInterface;
use App\Repositories\Laws\LawsInterface;
use DB;

class CouchbaseService implements Couchbase
{
    protected $lawsModel;
    protected $xmlObj;
    protected $couchbase;
    protected $limit = 200;
    protected $count = 0;

    public function __construct(CouchbaseInterface $couchbase, LawsInterface $lawsModel)
    {
        $this->xmlObj = new BraveXml;
        $this->lawsModel = $lawsModel;
        $this->couchbase = $couchbase;
        $this->limit = env('COUCHBASE_PAGE_LIMIT', 5);
        
        $this->xmlBack = storage_path() . DIRECTORY_SEPARATOR . config('code.solr_xml_backup') . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR;
        $this->xmlPath = storage_path() . DIRECTORY_SEPARATOR . config('code.solr_xml_file');
        //delete old solr xmldata. default 7
        $this->oldDir =  storage_path() . DIRECTORY_SEPARATOR . config('code.solr_xml_backup') . DIRECTORY_SEPARATOR . date('Y-m-d', strtotime("- " . env('SOLR_RETAINED_DAY', 7) . " day")) . DIRECTORY_SEPARATOR;
    }
    
    /*
     * Couchbase Server Initialization
     */
    public function init() {
        //Initialization Couchbase Cluster
        $this->couchbase->init();
        
        //Delete Old Solr Xml Data
        system('rm -rf ' . $this->xmlBack);
        system('rm -rf ' . $this->oldDir);
        mkdir($this->xmlBack, 0777, true) or die('create xolr xml is failure.\r\n');
    }
    
    public function getData() {
        $page = 1;
        DB::beginTransaction();
        $this->lawsModel->deleteAllData();
        do{
            $laws = $this->couchbase->getDataList($page, $this->limit);
            if($laws == false) {
                DB::rollBack();
                return false;
            }
            $laws = (array) $laws;
            //todo save law data and generate solr xml file
            $result = $this->dealData($laws['rows']);
            if ($result === false)
            {
                DB::rollBack();
                return false;
            }
            
            if($laws['metrics']['resultCount'] < $this->limit)
            {
                break;
            }
            //test
//            break;
            $page++;
        }while(true);
        
        DB::commit();
        
        $this->linkXmlFile();
        return true;
    }
    
    /**
     * 
     * @param array $laws
     * @return boolean
     */
    protected function dealData(array $rows)
    {
        $date = date('Y-m-d H:i:s');
        foreach ($rows as $item) {
            if(!$item['id']) {
                continue;
            }
            $this->count++;
            $lawItem = $this->couchbase->getDataByDocid($item['id']);
            echo "[{$this->count}] {$date} docid:{$item['id']} get data is " . (($lawItem === false) ? "failure." : "success.\r\n");
            if($lawItem === false) {
                continue;
            }
            $lawItem = (array) $lawItem;
            $law = $lawItem['rows'][0]['mobilekokuho'];
            
            $law['docid'] = $item['id'];
            $law['id'] = $item['id'];
            
            if (count($law['docnamerireki']) > 0) {
                $law['docnamerireki'] = implode(",", $law['docnamerireki']);
            } else {
                $law['docnamerireki'] = '';
            }
            $law['hourei_cd'] = $law['houreicd'];
            $law['hourei_name'] = $law['docname'];
            //save data to db
            $result = $this->lawsModel->saveData($law);
            echo "[{$this->count}] {$date} docid:{$law['id']} is save db " . (($result === false) ? "failure." : "success.\r\n");
            if ($result === false) {
                return false;
            }
            //save data to xml
            $result = $this->saveToXml($law);
            echo "[{$this->count}] {$date} docid:{$law['id']} is save xml " . (($result === false) ? "failure." : "success.\r\n");
            if ($result === false) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * 
     * @param array $law
     * @return boolean
     */
    public function saveToXml (array $law){
        $file = $this->xmlBack . $law['hourei_cd']. "_"  . $law['misekouflag'] . ".xml";
        $data = [
            'docid' => $law['docid'],
            'hourei_cd' => $law['hourei_cd'],
            'hourei_name' => $law['hourei_name'],
            'koufubi' => $law['koufubi'],
            'shikoubi' => $law['shikoubi'],
            'docnamerireki' => $law['docnamerireki'],
            'shikoubihyouki' => $law['shikoubihyouki'],
            'hatsurei' => $law['hatsurei'],
            'upddate' => $law['upddate'],
            'filesyubetsu' => $law['filesyubetsu'],
            'misekouflag' => $law['misekouflag'],
            'nextid' => '',
            'syomeicd' => $law['syomeicd'],
            'coid' => $law['coid'],
            'datalist' => '',
        ];
        if(file_exists($file)) {
            $data = $this->xmlObj->xmlToArray($file, true);
        }
        //nextid
        if ($law['nextid']) {
            $data['nextid'] .= ($data['nextid'] ? ',' : '') . $law['nextid'];
        }
        $datalist = '';
        foreach ($law['alldatalist'] as $item) {
            $datalist .= $item['danrakutext'] . "　";
            if(isset($item['table']['rows'])) {
                $datalist .= $this->dealTable($item['table']['rows']);
            }
        }
        $data['datalist'] .= strip_tags($datalist);
        
        $xmlStr = $this->xmlObj->arrayToXml($data);
        
        return file_put_contents($file, $xmlStr);
    }
    
    /**
     * deal table string
     * @param array $table
     * @return type
     */
    protected function dealTable($table) {
        $str = '';
        foreach ($table as $cols) {
            foreach($cols['cols'] as $cells) {
                foreach($cells['cells'] as $cell) {
                    $str .= $cell['celltext'] . "　";
                }
            }
        }
        return strip_tags($str);
    }
    
    /**
     * link solr path
     */
    public function linkXmlFile() {
        file_exists($this->xmlPath) ? system('rm -rf ' . $this->xmlPath) : null;
        $is_win = env('IS_WIN', FALSE);
        if (!$is_win) {
            symlink($this->xmlBack, $this->xmlPath);
        } else {
            exec('mklink /j "' . str_replace('/', '\\', $this->xmlPath) . '" "' . str_replace('/', '\\', $this->xmlBack) . '"');
        }
    }

}
