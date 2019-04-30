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
}