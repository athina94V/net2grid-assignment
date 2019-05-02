<?php

require_once __DIR__ . '\vendor\autoload.php';
require_once __DIR__ . '\message.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

$ini_array = parse_ini_file('config.ini');
$message_queue = $ini_array['message_queue'];

$url = 'https://zl04pypnbk.execute-api.eu-west-1.amazonaws.com/prod/results';

$connection = new AMQPStreamConnection($message_queue['hostname'], $message_queue['port'], $message_queue['username'], $message_queue['password']);
$channel = $connection->channel();
$binding_key = '#.#.#.#.#';

$exchange_name = 'results';
$exchange_type = 'topic';
$queue_name = 'raw_results';

$ttl = new AMQPTable(["x-message-ttl" => 3600000,]);
$channel->exchange_declare($exchange_name, $exchange_type, false, true, false);

list ($queue_name,, ) = $channel->queue_declare($queue_name, false, true, false, false, false, $ttl);
$channel->queue_bind($queue_name, 'results', $binding_key);
while (1) {
    $response = file_get_contents($ini_array['hostname']);
    $response = json_decode($response);


    Message::sendMessage($response, $channel);
    Message::receiveMessage($channel);
}

$channel->close();
$connection->close();
