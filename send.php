<?php

require_once __DIR__ . '\vendor\autoload.php';
require __DIR__ . '\convertValues.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class send {

    public static function sendMessage($response) {
        $hostname = 'candidatemq.n2g-dev.net';
        $port = 5672;
        $username = 'candidate';
        $password = 'efn[bjz*SV,~tw/r7=';

        $exchange_name = 'results';
        $exchange_type = 'topic';

        $connection = new AMQPStreamConnection($hostname, $port, $username, $password);
        $channel = $connection->channel();

        $channel->exchange_declare($exchange_name, $exchange_type, false, true, false);

        $routing_key = convertValues::convertValue($response);
        $msg = convertValues::prepareMessage($response);
        $channel->basic_publish($msg, 'results', $routing_key);


        $channel->close();
        $connection->close();
    }

}
