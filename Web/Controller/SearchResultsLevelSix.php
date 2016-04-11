<?php

namespace FrontNginx\Web\Controller;

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
    $xsl = new DOMDocument;
    $xsl->load('Adverts.xslt');
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);
    $html = $proc->transformToXML($adverts);
    $param = new TemplateParameter('searchResults', $html);
    outputPage(array($param));
}

function outputPage($param)
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('searchresultslevelsix.html', $param);
}

?>