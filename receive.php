<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class receive {

    public static function receiveMessage() {
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


        $channel->exchange_declare($exchange_name, $exchange_type, false, true, false);
        list ($queue_name,, ) = $channel->queue_declare($queue_name, false, true, false, false, false, $ttl);
        $binding_key = '#.#.#.#.#';

        $channel->queue_bind($queue_name, 'results', $binding_key);


        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        $callback = function ($msg) {
            echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname, 3306);
            $length = strlen ($msg->body);
            $test = str_split($msg->body, $length-13);
            $value = substr($msg->body, 0, $length-13);
            $timestamp = substr($msg->body, $length-13);
            $sql = "INSERT INTO results (timestamp, value) VALUES('$timestamp', '$value')";
            if ($conn->query($sql) === TRUE) {
                echo "New record  successfully";
            } else {
                echo "Error creating row " . $conn->error;
            }
            $conn->close();
        };
        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }

}
