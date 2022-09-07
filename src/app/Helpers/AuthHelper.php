<?php

namespace App\Helpers;

class AuthHelper
{
  /**
   * TODO: check if email is valid
   * @param email string
   * @return boolean
   */
  public function is_email(string $email)
  {
    return (filter_var($email, FILTER_VALIDATE_EMAIL));
  }

  /**
   * TODO: check if password same as confirm password
   * @param password string
   * @param confirm_password string
   * @return boolean
   */
  public function confirm_password(string $password, string $confirmPassword)
  {
    return $password == $confirmPassword;
  }

  /**
   * TODO: check if password is greater than or equal to length
   * @param password string
   * @param length int
   * @return boolean
   */
  public function password_length(int $length, string $password)
  {
    return strlen($password) >= $length;
  }
}
