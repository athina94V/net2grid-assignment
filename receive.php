<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$hostname = 'candidatemq.n2g-dev.net';
$port = 5672;
$username = 'candidate';
$password = 'efn[bjz*SV,~tw/r7=';

$queue_name = 'raw_results';
$ttl = new AMQPTable(["x-message-ttl" => 3600000,]);

$connection = new AMQPStreamConnection($hostname, $port, $username, $password);
$channel = $connection->channel();

$channel->queue_declare($queue_name, false, true, false, false, false, $ttl);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo " [x] Done\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};
$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
