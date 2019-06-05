<?php
class DownloadDao
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function getChallanData($id, $uid)
    {
        $sql = "select departmentid as DEPARTMENT_ID, grnno as GRN, office_code, amount from egras_response where id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 

        $row['ACTION_CODE'] = 'Y';

        // log it to egras_log
        $dao = new SubmitPaymentDao($this->pdo);
        $row_id = $dao->logData(array(
            'department_id' => $row['DEPARTMENT_ID'],
            'request_parameters' => json_encode($row),
            'u_id' => $uid,
            'activity' => "Challan Download"
        ));

        $postData = '';
        // convert response into the form 'key=value&key=value'
        foreach ($row as $key => $value) {
            $postData .= $key . "=" . $value . "&";
        }
        // remove the trailing "&" 
        $postData = trim(substr($postData, 0, -1));

        $data = array();
        $data['success'] = true;
        $data['data'] = $postData;
        $data['id'] = $row_id;
        $data['url'] = "http://103.8.248.139/challan/views/frmEpayEchallanPrintMerge.php";
 
        return $data;
    }


    public function updateChallanLog($arr)
    {
        $sql = "update egras_log set RESPONSEPARAMETERS = ?, datetime=localtimestamp(0) where id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $arr['data'], PDO::PARAM_STR);
        $stmt->bindValue(2, $arr['id'], PDO::PARAM_INT);
        $stmt->execute();

        $data = array();
        $data['success'] = true;
        $data['data'] = $stmt->rowCount();              // affected rows

        return $data;
    }
}
