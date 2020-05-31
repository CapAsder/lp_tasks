<?php
require_once "vendor/autoload.php";
define("ROOT", dirname(__FILE__));

$manager = new \workers\WorkerManager();
$manager->init();
$manager->run();
?>