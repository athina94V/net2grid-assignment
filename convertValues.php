<?php

use PhpAmqpLib\Message\AMQPMessage;

/**
 * 
 */
class convertValues {

/**
 * Convert values from hex to dec and prepare routing key
 * 
 * @param object $response
 * @return string $routing_key
 */  

    public static function convertValue($response) {
        
        $gateway_eui = hexdec($response->gatewayEui);
        $gateway_eui = number_format($gateway_eui, 0, '', '');
        $profile_id = hexdec($response->profileId);
        $endpoint_id = hexdec($response->endpointId);
        $cluster_id = hexdec($response->clusterId);
        $attribute_id = hexdec($response->attributeId);

        $routing_key = $gatewayEui.$profileId.$endpointId.$clusterId.$attributeId;
        return $routing_key;
    }
/**
 * convert message to an AMQP format
 * 
 * @param object $response
 * @return AMQPMessage $msg
 */
    
    public static function prepareMessage($response) {
        $msg = array('value' => $response->value, 'timestamp' => $response->timestamp);
        $msg = implode('', array_slice($msg, 0));
        echo "MSG->$msg";
        $msg = new AMQPMessage($msg);
        
        return $msg;
    }

}
