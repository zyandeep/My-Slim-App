<?php
class SearchChallanController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function serach($request, $response, $args) 
    {
        // get serach quey string
        $query = $request->getQueryParam('query');

        // get the uid
        $user = $request->getAttribute('user');
        $uid = $user->uid;

        // call DAO
        return $response->withJson($this->dao->getGrnSuggesstions($query, $uid));
    }

    public function fetch($request, $response, $args)
    {
        // get serach quey string
        $grn = $request->getQueryParam('grn');

        // get the uid
        $user = $request->getAttribute('user');
        $uid = $user->uid;
  
        // call DAO
        return $response->withJson($this->dao->getTransaction($grn, $uid));
    }
}
