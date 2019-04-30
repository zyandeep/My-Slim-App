<?php
class TransactionDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTransactions($page = 0)
    {
        $sql = "SELECT name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date, amount, status FROM"
                . " (SELECT office.name, grnno, challan_date, amount, status FROM"
                . " office, egras_response WHERE office.office_code =  egras_response.office_code"
                . " MINUS SELECT * FROM (SELECT office.name, grnno, challan_date, amount, status"
                . " FROM office, egras_response WHERE office.office_code =  egras_response.office_code"
                . " ORDER BY challan_date DESC )WHERE ROWNUM <= ?*3 ORDER BY challan_date DESC)"
                . " WHERE ROWNUM <= 3";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $page, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        $data['success'] = true;
        $data['data'] = [];

        foreach ($rows as $row) {
            array_push($data['data'], $row);
        }

        return $data;
    }
}
