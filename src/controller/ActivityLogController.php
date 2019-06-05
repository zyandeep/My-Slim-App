<?php

class ActivityLogController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }
    
    public function __invoke($request, $response, $args) 
    {
        // get the page number
        $page = $request->getQueryParam('page');
        
        // get the uid
        $user = $request->getAttribute('user');
        $uid = $user->uid;
  
        // call DAO
        return $response->withJson($this->dao->getActivityLogs($page, $uid));    
    }
}