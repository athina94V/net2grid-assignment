<?php

require_once __DIR__ . '\vendor\autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

$hostname = 'candidatemq.n2g-dev.net';
$port = 5672;
$username = 'candidate';
$password = 'efn[bjz*SV,~tw/r7=';

$exchange_name = 'results';
$exchange_type = 'topic';

$connection = new AMQPStreamConnection($hostname, $port, $username, $password);
$channel = $connection->channel();
if ($connection) {
    echo "I did it";
}
$channel->exchange_declare($exchange_name, $exchange_type, false, true, false);

$msg = array('value' => 141673, 'timestamp' => 155626955025);
$msg = implode('', array_slice($msg, 0));
$msg = new AMQPMessage($msg);

$routing_key = '9574384527443017728.260.11.1794.0';
$channel->basic_publish($msg, 'results', $routing_key);


$channel->close();
$connection->close();


