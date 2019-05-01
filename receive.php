<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class receive {

    public static function receiveMessage($message_queue) {

        $exchange_name = 'results';
        $exchange_type = 'topic';
        $queue_name = 'raw_results';
        $ttl = new AMQPTable(["x-message-ttl" => 3600000,]);

        $connection = new AMQPStreamConnection($message_queue['hostname'], $message_queue['port'], $message_queue['username'], $message_queue['password']);
        $channel = $connection->channel();

        $channel->exchange_declare($exchange_name, $exchange_type, false, true, false);
        list ($queue_name,, ) = $channel->queue_declare($queue_name, false, true, false, false, false, $ttl);
        $binding_key = '#.#.#.#.#';

        $channel->queue_bind($queue_name, 'results', $binding_key);


        $callback = function ($msg) {
            receive::insertRecord($msg);
        };
        
        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        if (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public static function insertRecord($msg) {
        $properties = parse_ini_file('config.ini');
        $database_details = $properties['database_details'];

        $conn = new mysqli($database_details['servername'], $database_details['username'], $database_details['password'], $database_details['dbname'], 3306);

        $value = substr($msg->body, 0, ($msg->body_size) - 13);
        $timestamp = substr($msg->body, ($msg->body_size) - 13);
        $sql = "INSERT INTO results (timestamp, value) VALUES('$timestamp', '$value')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error creating row " . $conn->error;
        }
        $conn->close();
    }

}
