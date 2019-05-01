<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class receive {

    public static function receiveMessage($message_queue,$channel) {

        $exchange_name = 'results';
        $exchange_type = 'topic';
        $queue_name = 'raw_results';
        $ttl = new AMQPTable(["x-message-ttl" => 3600000,]);

        $channel->exchange_declare($exchange_name, $exchange_type, false, true, false);


        $callback = function ($msg) {
            receive::insertRecord($msg);
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        if (count($channel->callbacks)) {
            $channel->wait();
        }

    }

    public static function insertRecord($msg) {
        $properties = parse_ini_file('config.ini');
        $database_details = $properties['database_details'];

        $conn = new mysqli($database_details['servername'], $database_details['username'], $database_details['password'], $database_details['dbname'], 3306);

        $value = substr($msg->body, 0, ($msg->body_size) - 13);
        $timestamp = substr($msg->body, ($msg->body_size) - 13);
        if (is_numeric($value) && is_numeric($timestamp)) {
            $sql = "INSERT INTO results (timestamp, value) VALUES('$timestamp', '$value')";
            if ($conn->query($sql) !== TRUE) {
                echo 'Error creating row ' . $conn->error;
            }
            echo "VALUE = $value && TIMESTAMP = $timestamp <br>";
        } else {
            echo 'Wrong data';
        }
        $conn->close();
    }

}
