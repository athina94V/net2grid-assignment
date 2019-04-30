<?php

require_once __DIR__ . '\vendor\autoload.php';
require __DIR__ . '\convertValues.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class send {

    public static function sendMessage($response) {
        $ini_array = parse_ini_file("config.ini");
        $message_queue = $ini_array[message_queue];


        $exchange_name = 'results';
        $exchange_type = 'topic';

        $connection = new AMQPStreamConnection($message_queue[hostname], $message_queue[port], $message_queue[username], $message_queue[password]);
        $channel = $connection->channel();

        $channel->exchange_declare($exchange_name, $exchange_type, false, true, false);

        $routing_key = convertValues::convertValue($response);
        $msg = convertValues::prepareMessage($response);
        $channel->basic_publish($msg, 'results', $routing_key);


        $channel->close();
        $connection->close();
    }

}
