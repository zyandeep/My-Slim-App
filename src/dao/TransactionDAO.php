<?php
class TransactionDAO
{
    private $pdo;
    private $rowNum;           

    public function __construct($pdo)
    {
        $this->pdo = $pdo;

        // fetching 10 records at a time
        $this->rowNum = 10;
    }

    public function getTransactions($json, $uid)         // an assoctiative array of all Qprarams
    {
        if (empty($json['year']) && empty($json['month1']) && empty($json['month2'])) {
            // for an unfiltered/general search

             $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop FROM"
                . " (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop, egras_response.datetime FROM"
                . " office, egras_response WHERE office.office_code =  egras_response.office_code AND u_id = ?"
                . " MINUS SELECT * FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop, egras_response.datetime"
                . " FROM office, egras_response WHERE office.office_code = egras_response.office_code AND u_id = ?"
                . " ORDER BY egras_response.datetime DESC) WHERE ROWNUM <= ? * $this->rowNum ORDER BY datetime DESC)"
                . " WHERE ROWNUM <= $this->rowNum";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $uid, PDO::PARAM_STR);
            $stmt->bindValue(2, $uid, PDO::PARAM_STR);
            $stmt->bindValue(3, $json['page'], PDO::PARAM_INT);
            $stmt->execute();   
        } 
        else {
            // do a filtered search

            $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop, egras_response.datetime FROM office, egras_response WHERE office.office_code =  egras_response.office_code  AND u_id = ? AND (EXTRACT(MONTH FROM challan_date)
            between ? and ? OR EXTRACT(MONTH FROM challan_date) between ? and ?) AND extract(YEAR FROM challan_date) = ? MINUS SELECT * FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop, egras_response.datetime FROM office, egras_response WHERE office.office_code = egras_response.office_code  AND u_id = ? AND (EXTRACT(MONTH FROM challan_date)
            between ? and ? OR EXTRACT(MONTH FROM challan_date) between ? and ?) AND extract(YEAR FROM challan_date) = ? ORDER BY egras_response.datetime DESC )WHERE ROWNUM <= ? * $this->rowNum ORDER BY datetime DESC) WHERE ROWNUM <= $this->rowNum";
            
            $stmt = $this->pdo->prepare($sql);
        
            $stmt->bindValue(1, $uid, PDO::PARAM_STR);
            $stmt->bindValue(2, $json['month1'], PDO::PARAM_INT);
            $stmt->bindValue(3, $json['month2'], PDO::PARAM_INT);
            $stmt->bindValue(4, $json['month2'], PDO::PARAM_INT);
            $stmt->bindValue(5, $json['month1'], PDO::PARAM_INT);
            $stmt->bindValue(6, $json['year'], PDO::PARAM_INT);
            $stmt->bindValue(7, $uid, PDO::PARAM_STR);
            $stmt->bindValue(8, $json['month1'], PDO::PARAM_INT);
            $stmt->bindValue(9, $json['month2'], PDO::PARAM_INT);
            $stmt->bindValue(10, $json['month2'], PDO::PARAM_INT);
            $stmt->bindValue(11, $json['month1'], PDO::PARAM_INT);
            $stmt->bindValue(12, $json['year'], PDO::PARAM_INT);
            $stmt->bindValue(13, $json['page'], PDO::PARAM_INT);

            $stmt->execute();
        }
        

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        $data['success'] = true;
        $data['result'] = [];

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;
    }


    public function getRecentTransactions($uid)
    {
        // fetch 5 recent transactions
        $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop"
                . " FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop"
                . " FROM office, egras_response WHERE office.office_code = egras_response.office_code AND u_id = ?"
                . " ORDER BY egras_response.datetime DESC) WHERE ROWNUM <= 5";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $uid, PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        
        $data = array();
        $data['success'] = true;
        $data['result'] = [];

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;
    }

    public function verifyPayment($id, $uid)
    {
        // get the parametes to verify a payment by the id

        $sql = "select office_code, departmentid as DEPARTMENT_ID, amount from egras_response where id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);      // since only one record

        // add ACTION_CODE and SUB_SYSTEM
        $row['ACTION_CODE'] = "GETCIN";
        $row['SUB_SYSTEM'] = "GRAS-APP";

        // log it to egras_log
        $dao = new SubmitPaymentDao($this->pdo);
        $dao->logData(array(
            'department_id' => $row['DEPARTMENT_ID'],
            'request_parameters' => json_encode($row),
            'u_id' => $uid,
            'activity' => "Verify Payment"
        ));
 

        $postData = '';
        // convert request json into the form 'key=value&key=value'
        foreach ($row as $key => $value) {
            $postData .= $key . "=" . $value . "&";
        }
        // remove the trailing "&" 
        $postData = trim(substr($postData, 0, -1));
      
        // Send data for android to POST on eGRAS site
        $data = array();
        $data['success'] = true;
        $data['data'] = $postData;
        $data['url'] = "http://103.8.248.139/challan/models/frmgetgrn.php";
 
        return $data;
    }
}