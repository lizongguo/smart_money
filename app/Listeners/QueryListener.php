<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use App\Libraries\BLogger;

/**
 * write query log
 * @author yutao
 * @date 2018-4-17 15:21:45
 */
class QueryListener {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        
    }
    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event) {
        if (config('app.db_log_flag') === false) {
            //Non write db log
            return true;
        }
        $sql = str_replace("%", "%%", $event->sql);
        $sql = str_replace("?", "'%s'", $sql);

        $log = vsprintf($sql, $event->bindings);

        BLogger::getLogger(BLogger::LOG_DB_QUERY)->info($log);

        //阻止后续事件执行
        return false;
    }
}
