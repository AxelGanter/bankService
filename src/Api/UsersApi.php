<?php

namespace OpenAPIServer\Api;

use OpenAPIServer\Api\AbstractPetApi;

class UsersApi extends AbstractUsersApi
{
    public function newTransaction($request, $response, $args)
    {
        $response->getBody()->write("not implemented yet");
        return $response->withStatus(418);        
    }
}

// Class | Method | HTTP request | Description
// ------------ | ------------- | ------------- | -------------
// *AbstractAdvisorsApi* | **newAccount** | **POST** /accounts | add new account
// *AbstractAdvisorsApi* | **newUser** | **POST** /users | add new user
// *AbstractAdvisorsApi* | **getUser** | **GET** /users/{id} | Get a user by ID
// *AbstractUsersApi* | **newTransaction** | **POST** /transactions | create a transaction

