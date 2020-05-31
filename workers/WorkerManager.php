<?php

namespace workers;

use Exception;
use GearmanWorker;
use util\Log;

class WorkerManager{
    private array $workers;
    private Log $log;
    private \GearmanWorker $main_worker;

    public function init(){
        $this->log = new Log('workers.log');
        $this->main_worker = new GearmanWorker();
        $this->main_worker->addServer('127.0.0.1');
        $this->workers = [
            new ProcessPayment(),
            new AmoCrmSendLead(),
        ];

        foreach ($this->workers as $worker){
            $worker->add_function($this->main_worker);
        }
    }

    public function run(){
        $pid = getmypid();
        while (true) {
            try {
                $this->log->put("Run worker pid:".$pid);
                $this->main_worker->work();
            } catch (Exception $e) {
                $this->log->put($e->getTraceAsString());
            }
            $code = $this->main_worker->returnCode();
            if ($code == GEARMAN_WORK_EXCEPTION) {
                $this->log->put("GEARMAN_WORK_EXCEPTION");
                continue;
            }
            if ($code != GEARMAN_SUCCESS) {
                $this->log->put('Worker '.$pid.' stopped with error: '.$code);
                break;
            }
        }
    }
}
?>