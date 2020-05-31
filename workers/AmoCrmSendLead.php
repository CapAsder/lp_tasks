<?php

namespace workers;

use Flagmer\Integrations\AmoCrm;
use Flagmer\Integrations\Amocrm\sendLeadDto;
use GearmanWorker;

class AmoCrmSendLead extends WorkerInterface{
    public function __construct(){
        $this->log_filename = "amo_crm_send_lead.log";
        parent::__construct();
    }

    public function add_function(GearmanWorker $worker){
        $amo_crm = new AmoCrm();
        $worker->addFunction('sendLead', function ($job) use ($amo_crm) {
            $params = \util\Convert::object_to_array(json_decode($job->workload()));
            $dto = new sendLeadDto();
            $dto->lead_id = $params['lead_id'];
            $this->log->put('worker ' . $this->pid . ': client:' . $job->workload());
            $amo_crm->sendLeadAction($dto);
        });
    }
}

?>