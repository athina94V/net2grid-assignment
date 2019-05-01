<?php

require __DIR__ . '\send.php';
require __DIR__ . '\receive.php';

$ini_array = parse_ini_file('config.ini');
$message_queue = $ini_array['message_queue'];

$url = 'https://zl04pypnbk.execute-api.eu-west-1.amazonaws.com/prod/results';
$i = 0;
while ($i < 10) {
    $response = file_get_contents($ini_array['hostname']);
    $response = json_decode($response);

    send::sendMessage($response,$message_queue);
    receive::receiveMessage($message_queue);
    $i++;
}

