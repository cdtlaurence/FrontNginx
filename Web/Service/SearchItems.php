<?php

namespace FrontNginx\Service;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Utilities\Security;
use FrontNginx\Web\Business\AdvertManagement;
use FrontNginx\Web\Model\TemplateParameter;
use \DOMDocument;

main();

function main()
{
    $security = new Security;
    $keywords = $security->cleanString($_GET['keywords']);
    $postcode = $security->cleanString($_GET['postcode']);
    $distance = $security->cleanString($_GET['distance']);
    $resultsOrder = $security->cleanString($_GET['resultsOrder']);
    $numberOfResults = $_GET['resultsNumber'];
    $advertManagement = new AdvertManagement;
    $results = $advertManagement->getAdvertsAdvanced($keywords, $postcode, $distance, $resultsOrder, $numberOfResults);
    $searchResultsHtml = createSearchResults($results);
    //$nodes = new DOMDocument();
    //$nodes->loadXML($results);
    //$number = $nodes->getElementsByTagName('Advert')->length;
    //$searchNumber = new TemplateParameter('searchNumber', $number);
    //$searchKeywords = new TemplateParameter('searchKeywords', $keywords);
    echo $searchResultsHtml;
    //outputPage(array($searchResultsHtml, $searchNumber, $searchKeywords));
}

function createSearchResults($results)
{
     $adverts = new DOMDocument();
     $adverts->loadXML($results);
     $advertManagement = new AdvertManagement;
     foreach ($adverts->getElementsByTagName('Advert') as $advert)
     {
     $advertId = $advert->getElementsByTagName('AdvertId')->item(0)->nodeValue;
     $title = $advert->getElementsByTagName('Title')->item(0)->nodeValue;
     $description = $advert->getElementsByTagName('Description')->item(0)->nodeValue;
     $insertedStamp = $advert->getElementsByTagName('InsertedStamp')->item(0)->nodeValue;
     $location = $advert->getElementsByTagName('Location')->item(0)->nodeValue;
     $html = $html . $advertManagement->createAdvertHtml($advertId, $title, $description, $insertedStamp, $location);
     }
     if (!$html)
     {
     $html = '<br/>Sorry, no results have been found. Please try a different search.';
     }
    return $html;
}

?>