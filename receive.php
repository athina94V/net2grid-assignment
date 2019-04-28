<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$hostname = 'candidatemq.n2g-dev.net';
$port = 5672;
$username = 'candidate';
$password = 'efn[bjz*SV,~tw/r7=';

$exchange_name = 'results';
$exchange_type = 'topic';
$queue_name = 'raw_results';
$ttl = new AMQPTable(["x-message-ttl" => 3600000,]);

$connection = new AMQPStreamConnection($hostname, $port, $username, $password);
$channel = $connection->channel();
//$test = $connection->isConnected();
//
//if ($test) {
//    print_r("it worked");
//}

$channel->exchange_declare($exchange_name, $exchange_types, false, true, false);
list ($queue_name,, ) = $channel->queue_declare($queue_name, false, true, false, false, false, $ttl);
$binding_key = '#.#.#.#.#';

    $channel->queue_bind($queue_name, 'results', $binding_key);

//$channel->queue_bind($queue_name, 'results');

//$channel->queue_declare($queue_name, false, true, false, false, false, $ttl);
//ini_set('max_execution_time', 0);
echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
};
$channel->basic_consume($queue_name, '', false, true, false, false, $callback);
//$msg = $channel->basic_get($queue_name);
//echo $msg;
while (count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
