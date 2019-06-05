<?php
class ActivityLogDao
{
    private $pdo;
    private $rowNum;           

    public function __construct($pdo)
    {
        $this->pdo = $pdo;

        // fetching 10 records at a time
        $this->rowNum = 5;
    }

    public function getActivityLogs($page, $uid)
    {
        $sql = "SELECT * FROM (SELECT egras_log.u_id, egras_response.grnno, egras_log.activity, egras_log.datetime, office.office_code, office.name FROM egras_log, egras_response, office WHERE egras_response.office_code = office.office_code AND egras_log.departmentid = egras_response.departmentid AND egras_log.u_id = ? MINUS SELECT * FROM (SELECT egras_log.u_id, egras_response.grnno, egras_log.activity, egras_log.datetime, office.office_code, office.name FROM egras_log, egras_response, office WHERE egras_response.office_code = office.office_code AND egras_log.departmentid = egras_response.departmentid AND egras_log.u_id = ? ORDER BY egras_log.datetime DESC) WHERE ROWNUM <= ? * $this->rowNum ORDER BY datetime DESC) WHERE ROWNUM <= $this->rowNum";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $uid, PDO::PARAM_STR);
        $stmt->bindValue(2, $uid, PDO::PARAM_STR);
        $stmt->bindValue(3, $page, PDO::PARAM_INT);
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