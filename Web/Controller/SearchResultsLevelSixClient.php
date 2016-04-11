<?php

namespace FrontNginx\Web\Controller;
header('Content-type: text/xml'); 
#error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Utilities\Security;
use FrontNginx\Web\Business\AdvertManagement;
use FrontNginx\Web\Model\TemplateParameter;
use \DOMDocument;
use \XSLTProcessor;

main();

function main()
{
    $security = new Security;
    $keywords = $security->cleanString($_GET['keywords']);
    $advertManagement = new AdvertManagement;
    $resultXml = $advertManagement->getAdvertsBySearch($keywords);
    $adverts = new DOMDocument();
    $adverts->loadXML($resultXml);
    $xslt = $adverts->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="Adverts.xslt"');
    $adverts->insertBefore($xslt, $adverts->documentElement);
    echo $adverts->saveXML();
}

function outputPage($param)
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('searchresultslevelsix.html', $param);
}

?>