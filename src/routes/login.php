<?php

return function ($app) {
  // Register auth middleware
  $auth = require __DIR__ . '/../middlewares/auth.php';

   // Add a login route
   $app->post('/api/login', function ($request, $response) {
     $data = $request->getParsedBody();
     if (!empty($data['username']) && !empty($data['password'])) {
       $username = $data['username'];
       $password = $data['password'];
       $user = new User($this->db);
       $userData = $user->logIn($username, $password);

       if(password_verify($password, $userData['password'])){
        $_SESSION['loggedIn'] = true;
        $_SESSION['userID'] = $userData['userID'];
        return $response->withJson($userData);
      }else{
        return $response->withStatus(401);
      }
    }
    else {
      return $response->withStatus(401);
    }
    });
    
  // Add a ping route
  $app->get('/api/ping', function ($request, $response, $args) {
    return $response->withJson(['loggedIn' => true]);
  })->add($auth);
};
