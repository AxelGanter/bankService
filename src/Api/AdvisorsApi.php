<?php

namespace OpenAPIServer\Api;

use OpenAPIServer\Api\AbstractAdvisorsApi;
use \PDO;

class AdvisorsApi extends AbstractAdvisorsApi
{
    private $pdo;

    public function __construct(){
        // $this->pdo = new PDO('mysql:host=localhost;dbname=bankservice', 'root', '');
        // $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // changes for heroku
        $dbopts = parse_url(getenv('DATABASE_URL'));
        echo json_encode($dbopts);
    }

    public function getUser($request, $response, $args)
    {
        $id = $args['id'];
        $message = "id = " . $id;
        

        $sql = "SELECT * FROM `user` WHERE id = :id";
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=bankservice', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array("id" => $id));
        } catch (PDOException $e) {
            //Do your error handling here
            $response->getBody()->write($e->getMessage()
                . " -stmt:" . json_encode($stmt) 
                . " -array:" . json_encode($array) 
                . " -errorInfo:" . json_encode($stmt->errorInfo())
            );
            return $response->withStatus(400);        
        }
        $response->getBody()->write(
            json_encode($stmt->fetch(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT)
        );
        return $response->withStatus(200);
    }

    public function newUser($request, $response, $args)
    {
        $bodyObject = $request->getParsedBody();
        if (!insertJsonFromApiIntoDatabase('user', $bodyObject, $response)){
            return $response->withStatus(400);        
        }else{
            return $response->withStatus(200);        
        }
    }

    public function renderHome($request, $response, $args)
    {
        $message = "Herzlich Willkommen";
        $response->getBody()->write($message);
        return $response->withStatus(200);
    }

    public function newAccount($request, $response, $args)
    {
        $bodyObject = $request->getParsedBody();
        if (!$this->insertJsonFromApiIntoDatabase('account', $bodyObject, $response)){
            return $response->withStatus(400);        
        }else{
            return $response->withStatus(200);        
        }
    }
    
    public function insertJsonFromApiIntoDatabase($table, $bodyObject, $response){
        
        $json = json_encode($bodyObject);
        $array = json_decode($json, true);

        $columns = implode(",",array_keys($array));
        $columnParameters = ":".implode(",:",array_keys($array));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($columnParameters)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($array);
        } catch (PDOException $e) {
            //Do your error handling here
            $response->getBody()->write($e->getMessage()
                . " -stmt:" . json_encode($stmt) 
                . " -array:" . json_encode($array) 
                . " -errorInfo:" . json_encode($stmt->errorInfo())
            );
            return false;
        }
        return true;
    }

}

// Class | Method | HTTP request | Description
// ------------ | ------------- | ------------- | -------------
// *AbstractAdvisorsApi* | **newAccount** | **POST** /accounts | add new account
// *AbstractAdvisorsApi* | **newUser** | **POST** /users | add new user
// *AbstractAdvisorsApi* | **getUser** | **GET** /users/{id} | Get a user by ID
// *AbstractUsersApi* | **newTransaction** | **POST** /transactions | create a transaction