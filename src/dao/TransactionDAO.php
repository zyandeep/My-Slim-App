<?php
class TransactionDAO
{
    private $pdo;
    private $rowNum;            // no of rows to fetch 

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->rowNum = 10;
    }

    public function getTransactions($params, $uid)         // an assoctiative array of all Qprarams
    {
        if (empty($params['year']) && empty($params['month1']) && empty($params['month2'])) {
            // for an unfiltered/general search

             $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop FROM"
                . " (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop FROM"
                . " office, egras_response WHERE office.office_code =  egras_response.office_code AND u_id = ?"
                . " MINUS SELECT * FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop"
                . " FROM office, egras_response WHERE office.office_code = egras_response.office_code AND u_id = ?"
                . " ORDER BY challan_date DESC) WHERE ROWNUM <= ? * $this->rowNum ORDER BY challan_date DESC)"
                . " WHERE ROWNUM <= $this->rowNum";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $uid, PDO::PARAM_STR);
            $stmt->bindValue(2, $uid, PDO::PARAM_STR);
            $stmt->bindValue(3, $params['page'], PDO::PARAM_INT);
            $stmt->execute();   
        } 
        else {
            // do a filtered search

            $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop FROM office, egras_response WHERE office.office_code =  egras_response.office_code  AND u_id = ? AND (EXTRACT(MONTH FROM challan_date)
            between ? and ? OR EXTRACT(MONTH FROM challan_date) between ? and ?) AND extract(YEAR FROM challan_date) = ? MINUS SELECT * FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop FROM office, egras_response WHERE office.office_code = egras_response.office_code  AND u_id = ? AND (EXTRACT(MONTH FROM challan_date)
            between ? and ? OR EXTRACT(MONTH FROM challan_date) between ? and ?) AND extract(YEAR FROM challan_date) = ? ORDER BY challan_date DESC )WHERE ROWNUM <= ? * $this->rowNum ORDER BY challan_date DESC) WHERE ROWNUM <= $this->rowNum";
            
            $stmt = $this->pdo->prepare($sql);
        
            $stmt->bindValue(1, $uid, PDO::PARAM_STR);
            $stmt->bindValue(2, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(3, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(4, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(5, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(6, $params['year'], PDO::PARAM_INT);
            $stmt->bindValue(7, $uid, PDO::PARAM_STR);
            $stmt->bindValue(8, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(9, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(10, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(11, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(12, $params['year'], PDO::PARAM_INT);
            $stmt->bindValue(13, $params['page'], PDO::PARAM_INT);

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
                . " ORDER BY challan_date DESC) WHERE ROWNUM <= 5";

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
}