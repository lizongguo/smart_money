<?php

namespace App\Libraries;

use DB;

/**
 * BraveFields
 *
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-1-18 15:38:50
 * @copyright   Copyright(C) bravesoft Inc.
 */
class BraveFields {

    protected $system = null;

    /**
     * 是否获取fields
     * @var type 
     */
    private $is_get_fields = false;
    private $fields = null;
    private $config_file_name = 'fields';
    protected $is_production = false;

    public function __construct(BraveSystem $system) {
        $this->system = $system;
        $this->is_production = config('app.env') == 'production' ? true : false;
        $this->fields_file = config_path() . DIRECTORY_SEPARATOR . $this->config_file_name . '.php';
        
        if (file_exists($this->fields_file) && $this->is_production) {
            $this->fields = config($this->config_file_name);
        } else {
            $this->getFields();
        }
    }

    /**
     * get db tables fields
     */
    protected function getFields() {
        $databaseName = config('database.connections.mysql.database');
        $rs = DB::select("select table_name, column_name from information_schema.columns where table_schema = '{$databaseName}'");
        $fields = [];
        foreach ($rs as $v) {
            $table_name = strtolower($v->table_name);
            $fields[$table_name][] = $v->column_name;
        }
        $this->fields = $fields;
        $this->is_get_fields = true;
        $this->saveFields();
    }

    /**
     * save tables fields to config file.
     */
    protected function saveFields() {

        $this->system->w($this->fields_file, "<?php\r\nreturn " . var_export($this->fields, true) . ";");
    }

    /**
     * get fields by db table
     * @param type $table
     */
    public function getFieldByTable($table) {
        $table = strtolower($table);

        //如果是正式环境，并且table表的字段找不到，并且，未刷新字段，则刷新一下数据字段。
        if ($this->is_production && !isset($this->fields[$table]) && false === $this->is_get_fields) {
            $this->getFields();
        }
        $field = isset($this->fields[$table]) ? $this->fields[$table] : [];
        return $field;
    }

}
