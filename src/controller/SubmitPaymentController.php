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

        // get the currently signed-in user
        $user = $request->getAttribute('user');

        // Do some processing and validation

        // JUST FOR TESTING PURPOSE, we are considering only one department and office
        // LRS => Land Records and Survey

        $arr['SUB_SYSTEM'] = "GRAS-APP";
        $arr["TREASURY_CODE"] = "BIL";
        $arr["MAJOR_HEAD"] = "0029";

    
        $id = $this->dao->getUniqueNumber();
        // it must be unique for each transaction
        // used to uniquely identify transactions at department portal
        $arr['DEPARTMENT_ID'] = "Ele". $id;

        // get the list of schemes
        $hoas = $arr['HOA'];
        unset($arr['HOA']);
        // add amount and hoas
        for ($i=1; $i <= count($hoas); $i++) { 
            $scheme = $hoas[$i-1];
            $arr['HOA' . $i] = $scheme['SCHEME_CODE'];
            $arr['AMOUNT' . $i] = $scheme['amount'];
        }

        unset($arr['SRO_CODE']);
        unset($arr['DISTRICT_CODE']);

        //  Write to egras_response and egras_log
        $this->dao->storeData(array(
            'department_id' => $arr['DEPARTMENT_ID'],
            'office_code' => $arr['OFFICE_CODE'],
            'request_parameters' => json_encode($arr),
            'amount' => $arr['CHALLAN_AMOUNT'],
            'u_id' => $user->uid
        ));

        $this->dao->logData(array(
            'department_id' => $arr['DEPARTMENT_ID'],
            'request_parameters' => json_encode($arr),
            'u_id' => $user->uid,
            'activity' => "Payment"
        ));


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