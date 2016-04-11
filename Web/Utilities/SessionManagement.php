<?php

namespace FrontNginx\Web\Utilities;

session_start();

class SessionManagement
{
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
        setcookie($name, $value, time() + (86400 * 30), "/"); // 86400 = 1 day
    }

    public function get($name)
    {
        $value = $_SESSION[$name];
        if(!$value)
        {
        $value = $_COOKIE[$cookie_name];
        }
        return $value;
    }
}

?>