<?php
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// {"DEPARTMENT_ID":"Ele2120","GRN":"AS000000990201920E","OFFICE_CODE":"LRS000","AMOUNT":"19","ACTION_CODE":"Y"}

$client = new Client(['base_uri' => 'http://103.8.248.139']);


//Print Challan
$response = $client->request('POST', '/challan/views/frmEpayEchallanPrintMerge.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2120',
        'GRN' => 'AS000000990201920E',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 19,
        'VIEWCHALLAN' => 'Y'
    ]
]);

/*
// GETGRN
$response = $client->request('POST', '/challan/models/frmgetgrn.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2120',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 19
    ]
]);


//GETCIN
$response = $client->request('POST', '/challan/models/frmgetgrn.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2120',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 19,
        'ACTION_CODE' => 'GETCIN',
        'SUB_SYSTEM' => 'GRAS-APP'
    ]
]);
*/


if ($response->getStatusCode() == 200) {
    $data = $response->getBody();

    echo $data;
}