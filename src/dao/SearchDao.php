<?php
class SearchDao
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getGrnSuggesstions($query, $uid)
    {
        $sql = "select grnno from egras_response where grnno like UPPER(?) and u_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, "%$query%", PDO::PARAM_STR);
        $stmt->bindValue(2, $uid, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        $data['success'] = true;
        $data['result'] = array();      // index array

        foreach ($rows as $row) {
            array_push($data['result'], $row['GRNNO']);
        }

        return $data;
    }


    public function getTransaction($grn, $uid)
    {
        $data = array();                            // the response

        $sql = "SELECT egras_response.id, office.name, grnno, to_char(challan_date, 'DD/MON/YYYY') as challan_date,"
                . " amount, status, mop FROM office, egras_response WHERE office.office_code = egras_response.office_code"
                . " AND u_id = ? and grnno = UPPER(?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $uid, PDO::PARAM_STR);
        $stmt->bindValue(2, $grn, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $data['success'] = true;
            $data['result'] = $row;
        } 
        else {
            // no records found
            $data['success'] = false;
            $data['result'] = null;
        }

        return $data;
    }
}
