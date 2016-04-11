<?php

namespace FrontNginx\Web\Service;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\AddressApiManagement;

header('Content-type: text/plain');
$addressApiManagement = new AddressApiManagement;
$postcode = $_GET['postcode'];
$result = $addressApiManagement->getAddress($postcode);
if(!($result['status'] == 200))
{
exit('Notfound');
}
exit('Found');

?>