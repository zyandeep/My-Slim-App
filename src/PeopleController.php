<?php
class PeopleController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPeople($request, $response, $args)
    {
        // query the database and get all people and send 
        // the result as JSON
        $data = array();
        $data['success'] = true;
        $data['data'] = [];

        $sql = "SELECT * FROM people";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            array_push($data['data'], $row);
        }

        return $response->withJson($data);
    }

    public function getPeopleById($request, $response, $args)
    {
        // query the database and get a single person and send 
        // the result as JSON
        $data = array();
        $data['success'] = true;
       
        $sql = "SELECT * FROM people WHERE id = $args[id]";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $data['data'] = $row;

        return $response->withJson($data);
    }

    public function createPerson($request, $response, $args)
    {
        $data = $request->getParsedBody();

        // validate and sanitise user inputs

        $sql = "insert into people(id, first_name, last_name, age) values(?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $data['id'], PDO::PARAM_INT);
        $stmt->bindValue(2, $data['f_name'], PDO::PARAM_STR);
        $stmt->bindValue(3, $data['l_name'], PDO::PARAM_STR);
        $stmt->bindValue(4, $data['age'], PDO::PARAM_INT);
        
        $res = $stmt->execute();

        return $response->withJson(array(
            'success' => $res
        ));
    }
}