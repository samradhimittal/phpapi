<?php
require_once "vendor/autoload.php";
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

$logger = new Logger('APIperformance');
$logstream = new StreamHandler('/var/log/monolog/php.log', Logger::INFO);
$logstream->setFormatter(new JsonFormatter());
$logger->pushHandler($logstream);

function myAPIcall() {
    $curl = curl_init();
    $url = 'http://dummy.restapiexample.com/api/v1/employees';
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

$logger->pushProcessor(function ($record) {
    $record['extra']['env'] = 'staging';
    $record['extra']['version'] = '1.1';
    return $record;
});

$start = microtime(TRUE); // A timestamp before the call
$result = myAPIcall();
$end = microtime(TRUE); // Another timestamp after the call

$rightnow = date('Ymd_His');
$status = "success";
$message = "user registered";
$logmessage = "\n".$rightnow.":".$status.","."$message";
file_put_contents("logfile.log", $logmessage, FILE_APPEND);