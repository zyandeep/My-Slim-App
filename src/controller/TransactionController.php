<?php
class TransactionController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function allTransactions($request, $response, $args) {
        //$page = $request->getQueryParam('page', 0);

        // all query parameters as an associative array
        $params = $request->getQueryParams();

        // call DAO
        return $response->withJson($this->dao->getTransactions($params));
    }
}