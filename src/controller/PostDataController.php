<?php
use Psr\Container\ContainerInterface;

class PostDataController
{
   protected $container;

   public function __construct(ContainerInterface $container) {
       $this->container = $container;
   }

   public function __invoke($request, $response, $args) {
       $data = $request->getParsedBody();       // an array
       $res = $this->doPost($data);
       return $response->withJson($res);
   }


   private function doPost($data) 
   {
        // making http calls with Guzzle PHP
        // POSTing data to httpbin.org

        $client = $this->container->get('guzzle');
        $response = $client->request('POST', '/post', ['json' => $data]);

        $data = array();

        if ($response->getStatusCode() == 200) {
            $data['success'] = true;
            $data['url'] = "http://192.168.43.211/my-projects/eGRAS/pg-input.php";
        }
        else {
            $data['success'] = false;
            $data['msg'] = "Data couldn't be submitted";
        }

        return $data;
    }
}