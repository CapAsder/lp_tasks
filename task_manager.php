<?php
require_once "vendor/autoload.php";
$g_client = new GearmanClient();
$g_client->addServer('127.0.0.1');

$g_client->setExceptionCallback(function ($task) {
    $data = $task->data();
    echo "exception {$data}\n";
});

$g_client->setCompleteCallback(function ($task) use (&$_clients) {
    $data = $task->data();
    echo "success {$data}\n";
});

try{
    $task_file_path = './tasks.json';
    if (!file_exists($task_file_path))
        throw new Exception('File tasks.json not found!');
    $json = file_get_contents($task_file_path);
    $tasks_lists = \util\Convert::object_to_array(json_decode($json));
    if (empty($tasks_lists))
        echo 'Tasks is empty';

    foreach ($tasks_lists as $task){
        if (!isset($task['task'])) {
            echo 'Not found tag "task"';
            continue;
        }
        if (!isset($task['data']))
            $task['data'] = [];
        $g_client->addTask($task['task'], json_encode($task['data']));
        echo 'Run '.$task['task'];
    }
    $g_client->runTasks();
    echo ($g_client->returnCode() == GEARMAN_SUCCESS ? 'success' : 'failure') . "\n";

}catch (Exception $exception){
    echo $exception->getMessage();
}
