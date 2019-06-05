<?php
class SubmitPaymentDao
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUniqueNumber()
    {
        $sql = "select dept_id_seq.nextval from dual";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['NEXTVAL'];
    }

    
    // every time, there's a request to eGras, log it to the database 
    // write data to egras_log
    public function logData($arr = null)
    {
        // get the ID to be inserted
        $stmt = $this->pdo->prepare('SELECT EGRAS_RESPONSE_LOG_SEQ.NEXTVAL AS nextInsertID FROM DUAL');
        $stmt->execute();
        $nextInsertId = $stmt->fetchColumn(0);

        $sql = "insert into egras_log(id, departmentid, requestparameters, u_id, activity) values(?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $nextInsertId, PDO::PARAM_INT);
        $stmt->bindValue(2, $arr['department_id'], PDO::PARAM_STR);
        $stmt->bindValue(3, $arr['request_parameters'], PDO::PARAM_STR);
        $stmt->bindValue(4, $arr['u_id'], PDO::PARAM_STR);
        $stmt->bindValue(5, $arr['activity'], PDO::PARAM_STR);
        $stmt->execute();

        return $nextInsertId;
    }

    // write data to egras_response
    public function storeData($arr = null)
    {
        $sql = "insert into egras_response (departmentid, office_code, requestparameters, u_id, amount)" 
                . " values(?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $arr['department_id'], PDO::PARAM_STR);
        $stmt->bindValue(2, $arr['office_code'], PDO::PARAM_STR);
        $stmt->bindValue(3, $arr['request_parameters'], PDO::PARAM_STR);
        $stmt->bindValue(4, $arr['u_id'], PDO::PARAM_STR);
        $stmt->bindValue(5, $arr['amount'], PDO::PARAM_INT);
        $stmt->execute();
    }
}