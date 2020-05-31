<?php

namespace workers;

use Exception;
use GearmanWorker;
use util\Log;

class WorkerInterface{
    protected string $log_filename = "worker_process.log";
    protected Log $log;
    protected int $pid;

    public function __construct(){
        $this->log = new Log($this->log_filename);
        $this->pid = getmypid();
    }

    public function add_function(GearmanWorker $worker){
        throw new Exception("Функция должна быть переопределена!");
    }
}
?>