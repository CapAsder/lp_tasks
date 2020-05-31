<?php

namespace workers;

use Exception;
use GearmanWorker;
use util\Log;

/**
 * Класс-интерфейс для всех будущих воркеров
 */

class WorkerInterface{
    /**
     * Каждый воркер может писать логи своего выполнения в свой файл
    */
    protected string $log_filename = "worker_process.log";
    protected Log $log;
    protected int $pid;

    public function __construct(){
        $this->log = new Log($this->log_filename);
        $this->pid = getmypid();
    }

    /**
     * Функция добавления в основной воркер инструкции на выполнение
    */
    public function add_function(GearmanWorker $worker){
        throw new Exception("Функция должна быть переопределена!");
    }
}
?>