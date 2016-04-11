<?php

namespace FrontNginx\Web\Service;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Business\UserManagement;

 header('Content-type: text/plain');
 $userName = $_GET['n'];
 if ( strlen($userName) < 6 )
 {
    exit('tooShort');
 }
 $userManagement = new UserManagement;
 if ($userManagement->getUser($userName))
 {
    exit('taken');
 }
 exit('available');

?>
