<?php
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// http://103.8.248.139/challan/views/frmEpayEchallanPrintMerge.php
// {"DEPARTMENT_ID":"Ele2117","GRN":"AS000000947201920E","OFFICE_CODE":"LRS000","AMOUNT":"12","ACTION_CODE":"Y"}


$client = new Client(['base_uri' => 'http://103.8.248.139']);

/*$response = $client->request('POST', '/challan/views/frmEpayEchallanPrintMerge.php', 
[
    'form_params' => [
        'DEPARTMENT_ID' => 'Ele2117',
        'GRN' => 'AS000000947201920E',
        'OFFICE_CODE' => 'LRS000',
        'AMOUNT' => 12,
        'ACTION_CODE' => 'Y'
    ],
]);
*/



$response = $client->request('POST', '/challan/models/frmgetgrn.php', 
        [
            'form_params' => [
                'DEPARTMENT_ID' => 'Ele2117',
                'OFFICE_CODE' => 'LRS000',
                'AMOUNT' => 12
            ],
        ]);

if ($response->getStatusCode() == 200) {
    $data = $response->getBody();

    echo $data;
}