<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use PDO;
use PDOException;
use \Firebase\JWT\JWT;

class AuthController extends Controller
{
  /**
   * TODO: login user
   * @param request get params
   * @param response json response
   * @return object token and user data
   */
  public function login($request, $response)
  {
    try {
      $sql = "
				SELECT 
          u.id, u.email, r.title as role
				FROM 
					users u
        LEFT JOIN
          roles r ON u.role_id = r.id
				WHERE 
					u.email = :email
				AND 
					u.password = :password
			";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':email' => $request->getParam('email'),
        ':password' => $request->getParam('password')
      ]);

      // Get logged in user's data
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Checked if user exist in database
      if (empty($user)) {
        return $response->withJSON([
          'success' => false,
          'status' => 404
        ]);
      }

      $token = [
        'iss' => 'utopian',
        'iat' => time(),
        'exp' => time() + 1000,
        'data' => $user
      ];

      $jwt = JWT::encode($token, $this->c->settings['jwt']['key']);

      return $response->withJson([
        'success' => true,
        'status' => 200,
        'jwt' => $jwt
      ]);
    } catch (PDOException $e) {
      // Catch all database errors
      return $response->withJson([
        'message' => $e->getMessage()
      ]);
    }
  }

  /**
   * TODO: register a new user
   * @param request get params
   * @param response json response
   * @return json
   */
  public function register($request, $response)
  {
    $auth_helper = new AuthHelper();
    $user = [
      'email' => $request->getParam('email'),
      'password' => $request->getParam('password'),
      'confirm_password' => $request->getParam('confirm_password')
    ];
    $password_length = 8;

    try {
      $sql = "
				INSERT INTO users
					(email, password, created_at)
				VALUES
					(:email, :password, :created_at)
			";

      $stmt = $this->c->db->prepare($sql);

      // Checked if user's email exist in database
      if ($this->email_exist($user['email'])) {
        return $response->withJSON([
          'message' => 'Email already exists.',
          'status' => 500
        ]);
      }

      // Check if email is valid
      if (!($auth_helper->is_email($user['email']))) {
        return $response->withJSON([
          'message' => "Email is invalid.",
          'status' => 500
        ]);
      }

      // Check if password is same with confirm password
      if (!($auth_helper->confirm_password($user['password'], $user['confirm_password']))) {
        return $response->withJSON([
          'message' => "Password are not same.",
          'status' => 500
        ]);
      }

      // Check if password length is greater than passed value
      if (!($auth_helper->password_length($password_length, $user['password']))) {
        return $response->withJSON([
          'message' => 'Password need at least ' . $password_length . ' characters.',
          'status' => 500
        ]);
      }

      $stmt->execute([
        ':email' => $user['email'],
        ':password' => $user['password'],
        ':created_at' => date('Y-m-d H:i:s')
      ]);

      return $response->withJSON([
        'message' => 'success',
        'status' => 200
      ]);
    } catch (PDOException $e) {
      return $response->withJSON([
        'message' => $e->getMessage()
      ]);
    }
  }

  private function email_exist(string $email)
  {
    $sql = "
			SELECT 
				id 
			FROM 
				users
			WHERE 
				email = :email
		";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_OBJ);

    return !empty($user);
  }
}
