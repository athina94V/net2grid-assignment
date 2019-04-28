<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of convertValues
 *
 * @author Athina
 */
class convertValues {

    //put your code here

    public static function convertValue($response) {
        $gatewayEui = hexdec($response->gatewayEui);
        $gatewayEui = number_format($gatewayEui, 0, '', '');
        $profileId = hexdec($response->profileId);
        $endpointId = hexdec($response->endpointId);
        $clusterId = hexdec($response->clusterId);
        $attributeId = hexdec($response->attributeId);

        return "$gatewayEui.$profileId.$endpointId.$clusterId.$attributeId";
    }

}