<?php
class GetDataController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function departments($request, $response, $args)
    {
        // call DAO
        return $response->withJson($this->dao->getDepartments());
    }

    public function paymenttypes($request, $response, $args)
    {
        return $response->withJson($this->dao->getPaymentTypes());
    }

    public function districts($request, $response, $args)
    {
        $dept_code = $args['dept_code'];
        return $response->withJson($this->dao->getDistricts($dept_code));
    }

    public function offices($request, $response, $args)
    {
        $dept_code = $args['dept_code'];
        $district_code = $args['district_code'];
        return $response->withJson($this->dao->getOffices($dept_code, $district_code));
    }

    public function schemes($request, $response, $args)
    {
        $office_code = $args['office_code'];
        return $response->withJson($this->dao->getSchemes($office_code));
    }
}