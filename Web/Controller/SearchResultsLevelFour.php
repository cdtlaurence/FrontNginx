<?php

namespace FrontNginx\Web\Controller;

header('Content-type: text/xml'); 

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Utilities\Security;
use FrontNginx\Web\Business\AdvertManagement;
use FrontNginx\Web\Model\TemplateParameter;

main();

function main()
{
    $security = new Security;
    $keywords = $security->cleanString($_GET['keywords']);
    $advertManagement = new AdvertManagement;
    echo $advertManagement->getAdvertsBySearch($keywords);
}

?>