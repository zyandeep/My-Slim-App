<?php
require '../../../vendor/autoload.php';

use GuzzleHttp\Client;

class EgrasResponse
{
    private $client;                // Guzzle http client
    private $pdo;

    public function __construct()
    {
        // create the http client
        $this->client = new Client(['base_uri' => 'http://103.8.248.139']);

        // create the db connection
        $dns = 'oci:dbname=localhost/XE';
        $username = 'zyandeep';
        $password = '123qweASD';

        $this->pdo = new PDO($dns, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }


    public function getGRN($dept_id, $office_code, $amount)
    {
        $response = $this->client->request('POST', '/challan/models/frmgetgrn.php', 
        [
            'form_params' => [
                'DEPARTMENT_ID' => $dept_id,
                'OFFICE_CODE' => $office_code,
                'AMOUNT' => $amount
            ],
            
            'synchronous' => true
        ]);

        // hopefully, we get STATUS this time
        if ($response->getStatusCode() == 200) {
            $data = $response->getBody();

            $arr = explode('$', $data);

            if ($arr[15] == 'STATUS' && !empty($arr[16])) {
                return $arr;
            }
        }

        return null;            // unsuccessfull GETGRN request
    }


    public function getOfficeCode($dept_id)
    {
        $sql = "select office_code from egras_response where departmentid = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $dept_id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);          // since, only one row
        
        return $row['OFFICE_CODE'];
    }


    public function updateTransaction($arr)
    {
        $sql = "update egras_response set grnno=?, responseparameters=?, amount=?, cin=?, challan_date=to_date(?, 'dd/mm/yyyy'), status=?, mop=? where departmentid=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($arr);

        return $stmt->rowCount();           // no. of affected rows
    }

    public function logTransaction($arr)
    {
        $sql = "update egras_log set responseparameters=?, datetime=localtimestamp(0) where departmentid=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($arr);

        return $stmt->rowCount();           // no. of affected rows
    }
}