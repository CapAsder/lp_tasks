<?php
namespace util;

use DateTime;

class Log {

    private string $log_file = 'error.log';
    private string $log_file_path = '';
    private $fp = null;
    private int $rid = 0;

    public function __construct($log_file = 'error.log'){
        $this->log_file = $log_file;
        $this->rid = rand(0, 65535);

        $this->log_file_path = ROOT.'/log/'.$this->log_file;
        $this->fp = fopen($this->log_file_path, "a");
        if (!$this->fp)
            $this->fp = fopen(ROOT.'/log/'.'error.log',"a");
    }

    public function put($data){
        $date = new DateTime();
        if (is_array($data))
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        fputs($this->fp, "[ ".$date->format('Y-m-d H:i:s').' ]: RID - '.$this->rid.'. '.$data."\n");
    }

    public function close(){
        $this->put('Log Finished!');
        fclose($this->fp);
    }

}