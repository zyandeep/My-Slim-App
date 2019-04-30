<?php
class SubmitPaymentController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function __invoke($request, $response, $args) 
    {
        // converting json input into an associative array <key => value>
        $arr = $request->getParsedBody();                   

        // Do some processing and validation
        //  1. add sub_system and department_id
        //  2. remove scheme names from HOA
        //  3. remove SRO_CODE and DISTRICT_CODE
        //  4. write to egras_response/log

        // JUST FOR TESTING PURPOSE
        $arr["DEPT_CODE"] = "LRS";
        $arr["OFFICE_CODE"] = "LRS000";
        $arr['SUB_SYSTEM'] = "LRC-EMOJNI";
    
        $id = $this->dao->getUniqueNumber();
        // it must be unique for each transaction
        // use to identify transaction at department portal
        $arr['DEPARTMENT_ID'] = "Ele". $id;

        //$districtCode = $arr['DISTRICT_CODE'];
        //$sroCode = $arr['SRO_CODE'];

        // get the list of schemes
        $hoas = $arr['HOA'];
        unset($arr['HOA']);
        // add addresses and hoas
        for ($i=1; $i <= count($hoas); $i++) { 
            $scheme = $hoas[$i-1];
            $arr['HOA' . $i] = $scheme['SCHEME_CODE'];
            $arr['AMOUNT' . $i] = $scheme['amount'];
        }


        $arr["HOA1"] = "0029-00-101-0000-000-01";
        unset($arr['SRO_CODE']);
        unset($arr['DISTRICT_CODE']);

        // convert '$arr' to a string of the form 'key=value&key=value'
        $postData = '';
        foreach ($arr as $key => $value) {
            $postData .= $key . "=" . $value . "&";
        }
        // remove the trailing "&" 
        $postData = trim(substr($postData, 0, -1));
        
        // Send data for android to POST on eGRAS site
        $data = array();
        $data['success'] = true;
        $data['data'] = $postData;
        $data['url'] = "http://103.8.248.139/challan/views/frmgrnfordept.php";

        return $response->withJson($data);
    } 
}