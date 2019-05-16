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
        $sql = "insert into egras_log(departmentid, requestparameters) values(?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $arr['department_id'], PDO::PARAM_STR);
        $stmt->bindValue(2, $arr['request_parameters'], PDO::PARAM_STR);
        $stmt->execute();
    }

    // write data to egras_response
    public function storeData($arr = null)
    {
        $sql = "insert into egras_response (departmentid, office_code, requestparameters, mobileno, amount, u_id)" 
                . " values(?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $arr['department_id'], PDO::PARAM_STR);
        $stmt->bindValue(2, $arr['office_code'], PDO::PARAM_STR);
        $stmt->bindValue(3, $arr['request_parameters'], PDO::PARAM_STR);
        $stmt->bindValue(4, $arr['mobile'], PDO::PARAM_INT);
        $stmt->bindValue(5, $arr['amount'], PDO::PARAM_INT);
        $stmt->bindValue(6, $arr['u_id'], PDO::PARAM_STR);
        $stmt->execute();
    }
}