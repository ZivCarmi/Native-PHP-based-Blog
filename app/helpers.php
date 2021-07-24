<?php

require_once 'db_config.php';

if (!function_exists('old')) {
  /**
   *
   * Returns the last input value of a field
   *
   * @param string $field_name The field name
   * @return string
   *
   */
  function old($field_name)
  {
    return $_REQUEST[$field_name] ?? '';
  }
}

if (!function_exists('csrf')) {
  /**
   *
   * Generates random string for security
   *
   * @return      string
   *
   */
  function csrf()
  {
    $token = sha1(rand(1, 10000) . '$$' . rand(1, 1000) . 'project');
    $_SESSION['csrf_token'] = $token;
    return $token;
  }
}

if (!function_exists('user_auth')) {
  /**
   *
   * Checks if user has the same IP & user agent 
   *
   * @return      boolean
   *
   */
  function user_auth()
  {

    $auth = false;

    if (isset($_SESSION['user_id'])) {

      if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR']) {

        if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']) {

          $auth = true;
        }
      }
    }


    return $auth;
  }
}

if (!function_exists('email_exists')) {
  /**
   *
   * Check if email is already been registered
   *
   * @param    string  $link The connection to database
   * @param    string  $email The registered email
   * @return      boolean
   *
   */
  function email_exists($link, $email)
  {
    $exist = false;
    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $exist = true;
    }
    return $exist;
  }
}
