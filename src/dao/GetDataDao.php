<?php
class GetDataDao  
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getDepartments()
    {
        $sql = "SELECT name, dept_code FROM department";

        // will return data as json object
        $data = array();                                    // associative array
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data['success'] = true;
        $data['result'] = array();      // index array

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;
    }

    public function getPaymentTypes()
    {
        $sql = "SELECT name, payment_type FROM payment";

        // will return data as json object
        $data = array();                                    // associative array
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data['success'] = true;
        $data['result'] = array();      // index array

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;
    }

    public function getDistricts($dept_code)
    {
        $sql = "SELECT district.name, district.district_code FROM district, dept_district"
                . " WHERE dept_district.dept_code = ?"
                . " AND dept_district.district_code = district.district_code";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $dept_code, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(); 
        $data['success'] = true;
        $data['result'] = array();      // index array

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;
    }

    public function getOffices($dept_code, $district_code)
    {
        $sql = "SELECT office.name, office.office_code, office.sro_code FROM office"
                . " WHERE district_code = ? AND dept_code = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $district_code, PDO::PARAM_INT);
        $stmt->bindValue(2, $dept_code, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(); 
        $data['success'] = true;
        $data['result'] = array();      // index array

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;
    }

    public function getSchemes($office_code)
    {
        $sql = "SELECT scheme_name, scheme_code FROM scheme"
                . " WHERE office_code = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $office_code, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(); 
        $data['success'] = true;
        $data['result'] = array();      // index array

        foreach ($rows as $row) {
            array_push($data['result'], $row);
        }

        return $data;        
    }
}