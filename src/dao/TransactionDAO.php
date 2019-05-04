<?php
class TransactionDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTransactions($params)         // an assoctiative array of all Qprarams
    {
        if (empty($params['year']) && empty($params['month1']) && empty($params['month2'])) {
            // for an unfiltered/general search

             $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop FROM"
                . " (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop FROM"
                . " office, egras_response WHERE office.office_code =  egras_response.office_code"
                . " MINUS SELECT * FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop"
                . " FROM office, egras_response WHERE office.office_code = egras_response.office_code"
                . " ORDER BY challan_date DESC) WHERE ROWNUM <= ?*4 ORDER BY challan_date DESC)"
                . " WHERE ROWNUM <= 4";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $params['page'], PDO::PARAM_INT);
            $stmt->execute();   
        } 
        else {
            // do a filtered search

            $sql = "SELECT id, name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status, mop FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop FROM office, egras_response WHERE office.office_code =  egras_response.office_code AND (EXTRACT(MONTH FROM challan_date)
            between ? and ? OR EXTRACT(MONTH FROM challan_date) between ? and ?) AND extract(YEAR FROM challan_date) = ? MINUS SELECT * FROM (SELECT egras_response.id, office.name, grnno, challan_date, amount, status, mop FROM office, egras_response WHERE office.office_code =  egras_response.office_code AND (EXTRACT(MONTH FROM challan_date)
            between ? and ? OR EXTRACT(MONTH FROM challan_date) between ? and ?) AND extract(YEAR FROM challan_date) = ? ORDER BY challan_date DESC )WHERE ROWNUM <= ?*4 ORDER BY challan_date DESC) WHERE ROWNUM <= 4";
            
            $stmt = $this->pdo->prepare($sql);
        
            $stmt->bindValue(1, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(2, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(3, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(4, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(5, $params['year'], PDO::PARAM_INT);
            $stmt->bindValue(6, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(7, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(8, $params['month2'], PDO::PARAM_INT);
            $stmt->bindValue(9, $params['month1'], PDO::PARAM_INT);
            $stmt->bindValue(10, $params['year'], PDO::PARAM_INT);
            $stmt->bindValue(11, $params['page'], PDO::PARAM_INT);

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
}