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

    public function repeatPayment($request, $response, $args)
    {
        $id = $args['id'];
        return $response->withJson($this->dao->repeatPayment($id));
    }

    public function verifyPayment($request, $response, $args)
    {
        $id = $args['id'];
        return $response->withJson($this->dao->verifyPayment($id));
    }
}