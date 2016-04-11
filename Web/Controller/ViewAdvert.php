<?php

namespace FrontNginx\Web\Controller;

#error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Business\AdvertManagement;
use FrontNginx\Web\Model\TemplateParameter;
use \DOMDocument;
main();

function main()
{
    $advertId = trim($_GET['advertId']);
    $location = trim($_GET['location']);
    $advertManagement = new AdvertManagement;
    $results = $advertManagement->getAdvert($advertId, $location);
    $advert = new DOMDocument();
    $advert->loadXML($results);
    $imagesHtml = $advertManagement->getImageHtml($advertId, $location, $advert->getElementsByTagName('Title')->item(0)->nodeValue, false);
    $advertTitle = new TemplateParameter('advertTitle', $advert->getElementsByTagName('Title')->item(0)->nodeValue);
    $advertDescription = new TemplateParameter('advertDescription',$advert->getElementsByTagName('Description')->item(0)->nodeValue);
    $advertImages = new TemplateParameter('advertImages', $imagesHtml);
    outputPage(array($advertTitle, $advertDescription, $advertImages));
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('viewadvert.html', $templateParameters);
}

?>