<?php
class OfficeController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getOffices($request, $response, $args)
    {
        $deptCode = $args['dept_code'];
        $districtCode = $args['district_code'];

        $data = array();
        $data['success'] = true;
        $data['data'] = [];

        $sql = "select * from office where dept_code=? and district_code=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $deptCode, PDO::PARAM_STR);
        $stmt->bindValue(2, $districtCode, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            array_push($data['data'], $row);
        }

        return $response->withJson($data);
    }
}