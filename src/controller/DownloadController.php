<?php

class DownloadController
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function download($request, $response, $args) 
    {
        // get the ID
        $id = $request->getQueryParam('id');

        // get the uid
        $user = $request->getAttribute('user');
        $uid = $user->uid;

        // call DAO
        return $response->withJson($this->dao->getChallanData($id, $uid));
    }

    public function update($request, $response, $args)
    {
        $params = $request->getParsedBody();

        return $response->withJson($this->dao->updateChallanLog($params));
    }
}
