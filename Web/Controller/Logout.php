<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Business\UserManagement;

$userManagement = new UserManagement();
$userManagement->logout();
header('Location: Index.php');
exit();

?> 