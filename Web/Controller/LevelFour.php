<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Model\TemplateParameter;
use FrontNginx\Web\Utilities\PageManagement;

main();

function main()
{
    outputPage();
}

function outputPage()
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('levelfour.html', null);
}

?>