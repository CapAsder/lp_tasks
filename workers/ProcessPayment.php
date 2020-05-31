<?php

namespace workers;

use Flagmer\Billing\Account;
use Flagmer\Billing\Account\processPaymentDto;
use GearmanWorker;

class ProcessPayment extends WorkerInterface {
    public function __construct(){
        $this->log_filename = "process_payment_worker.log";
        parent::__construct();
    }

    public function add_function(GearmanWorker $worker){
        $account = new Account();

        $worker->addFunction('processPayment', function ($job) use ($account) {
            $params = \util\Convert::object_to_array(json_decode($job->workload()));
            $dto = new processPaymentDto();
            $dto->account_id = $params['account_id'];
            $dto->amount = $params['amount'];
            $this->log->put('worker ' . $this->pid . ': data:' . $job->workload());
            $account->processPaymentAction($dto);
        });

    }
}
?>