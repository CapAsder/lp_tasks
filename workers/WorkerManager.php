<?php

namespace workers;

use Exception;
use GearmanWorker;
use util\Log;

/**
 * Менеджер, который собирает все воркеры в один и добавляет гермону функции на выполнение
 */
class WorkerManager{
    /**
     * Список классов, реализующие функционал воркера
     */
    private array $workers;
    /**
     * Менеджер, который записывает данные в лог
     */
    private Log $log;
    /**
     *  Основной воркер
     */
    private \GearmanWorker $main_worker;

    /**
     * Функция инициализации воркера
    */
    public function init(){
        $this->log = new Log('workers.log');
        $this->main_worker = new GearmanWorker();
        $this->main_worker->addServer('127.0.0.1');
        // при появлении нового воркера в проект, просто добавляем сюда новую запись
        $this->workers = [
            new ProcessPayment(),
            new AmoCrmSendLead(),
        ];
        // инициализируем функции воркера
        foreach ($this->workers as $worker){
            $worker->add_function($this->main_worker);
        }
    }

    /**
     * Функция запуска воркера
     */
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