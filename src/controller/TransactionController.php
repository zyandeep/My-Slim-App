<?php
class TransactionController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function allTransactions($request, $response, $args) {
        // all query parameters as an associative array
        $params = $request->getQueryParams();

         // get the uid
         $user = $request->getAttribute('user');
         $uid = $user->uid;

        // call DAO
        return $response->withJson($this->dao->getTransactions($params, $uid));
    }

    public function recentTransactions($request, $response, $args)
    {
        // get the uid
        $user = $request->getAttribute('user');
        $uid = $user->uid;

        // call DAO
        return $response->withJson($this->dao->getRecentTransactions($uid));
    }

    public function verifyPayment($request, $response, $args)
    {
        $id = $args['id'];
         
        // get the uid
        $user = $request->getAttribute('user');
        $uid = $user->uid;

        return $response->withJson($this->dao->verifyPayment($id, $uid));
    }

    // to verify payment from the webview
    public function insert($request, $response, $args)
    {
        // get POST params
        $params = $request->getParsedBody();

        // get the uid
        $user = $request->getAttribute('user');

        $params['u_id']= $user->uid;
        $params['activity'] = "Verify Payment";

        return $response->withJson($this->dao->insertLog($params));
    }
}