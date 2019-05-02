<?php
/**
 * Class contains functions to send, receive and store messages
 */
require_once __DIR__ . '\convertValues.php';


class message {
    
    /**
    * Send message to exchange
    * 
    *
    * @param string $response
    * @param type $channel
    */
    
    public static function sendMessage($response, $channel) {

        $routing_key = convertValues::convertValue($response);
        $msg = convertValues::prepareMessage($response);

        $channel->basic_publish($msg, 'results', $routing_key);
    }
    
    /**
     * Receive message from queue
     * 
     * @param type $channel
     */
    
    public static function receiveMessage($channel) {

        $exchange_name = 'results';
        $exchange_type = 'topic';
        $queue_name = 'raw_results';


        $channel->exchange_declare($exchange_name, $exchange_type, false, true, false);


        $callback = function ($msg) {
            message::insertRecord($msg);
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        if (count($channel->callbacks)) {
            $channel->wait();
        }
    }
    
    /**
     * Insert message to table in DB
     * 
     * @param string $msg
     * @throws Exception If msg is not numeric
     */
    
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
