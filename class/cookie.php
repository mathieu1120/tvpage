<?php

class Cookie
{
  static public function update($name, $value)
  {
    //To prevent XSS attacks, it is always better to set all parameters so we can set httponly to true.
    return setcookie($name, $value, time() + 365*24*3600, null, null, false, true);
  }

  static public function get($name)
  {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
  }
}