<?php

namespace FrontNginx\Web\Business;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\DataAccess\ConnectionFactory;
use FrontNginx\Web\DataAccess\Database;
use FrontNginx\Web\Utilities\SessionManagement;
use \DOMDocument;

class AdvertManagement
{
    public function addAdvert($title, $description, $images)
    {
        $sessionManagement = new SessionManagement;
        $user = $sessionManagement->get('user');
        $advertId = $this->insertAdvert($title, $description, $user->UserId);
        $this->insertImageLocations($advertId, $images);
    }

    public function updateAdvert($advertId, $title, $description, $images)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('Update Adverts Set Title = ?, Description = ? where advertId = ?');
        $statement->bindValue(1, $title, \PDO::PARAM_STR);
        $statement->bindValue(2, $description, \PDO::PARAM_STR);
        $statement->bindValue(3, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        $database->update($statement);
        $this->insertImageLocations($advertId, $images);
    }

    public function removeAdvert($advertId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('delete from Adverts where AdvertId = ?');
        $statement->bindValue(1, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        $database->delete($statement);
    }

     public function RemoveImage($imageId)
     {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('Delete from Images where ImageId = ?');
        $statement->bindValue(1, $imageId, \PDO::PARAM_INT);
        $database = new Database;
        $database->insert($statement);
     }

    public function getAdverts($userId)
    {
        return $this->getAdvertsByUserId($userId);
    }

    public function getAdvert($advertId, $location)
    {
        if($location == 'Greenwich')
            {
                $urlApache = 'http://stuweb.cms.gre.ac.uk/~lj231/WebServicesApache/Service/Services/GetAdvertsBySearch.php?term=' . $advertId;
                $result = new DOMDocument(); 
                $result->load($urlApache);
            } else
            {
                $urlIis = 'http://stuiis.cms.gre.ac.uk/lj231/SOA/WebServicesIIS.Service/ItemSearch.asmx/GetAdvert?advertId=' . $advertId;
                $result = new DOMDocument(); 
                $result->load($urlIis);
            }

        return $result->saveXML(); 
    }

    public function getImages($advertId, $location)
    {
        if($location == 'Greenwich')
            {
                $urlApache = 'http://stuweb.cms.gre.ac.uk/~lj231/WebServicesApache/Service/Services/GetAdvertsBySearch.php?term=' . $advertId;
                $result = new DOMDocument(); 
                $result->load($urlApache);
            } else
            {
                $urlIis = 'http://stuiis.cms.gre.ac.uk/lj231/SOA/WebServicesIIS.Service/ItemSearch.asmx/GetImages?advertId=' . $advertId;
                $result = new DOMDocument(); 
                $result->load($urlIis);
            }

        return $result->saveXML();
    }

    public function getImage($imageId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Images where ImageId = ?');
        $statement->bindValue(1, $imageId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement)[0];
    }

    public function getAdvertsBySearch($keywords)
    {
        $urlApache = 'http://stuweb.cms.gre.ac.uk/~lj231/WebServicesApache/Service/Services/GetAdvertsBySearch.php?term=' . $keywords;
        $apacheResult = new DOMDocument(); 
        $apacheResult->load($urlApache);
        $urlIis = 'http://stuiis.cms.gre.ac.uk/lj231/SOA/WebServicesIIS.Service/ItemSearch.asmx/GetAdvertsBySearch?searchTerm=' . $keywords;
        $iisResult = new DOMDocument(); 
        $iisResult->load($urlIis);
        $xmlRoot = $iisResult->documentElement; 
        foreach ( $apacheResult->documentElement->childNodes as $apacheNode ) 
        { 
           $iisNode = $iisResult->importNode($apacheNode,true); 
           $xmlRoot->appendChild($iisNode); 
        }

        return $iisResult->saveXML(); 
    }

        public function getAdvertsAdvanced($searchTerms, $postcode, $distance, $resultsOrder, $numberOfResults = 10)
        {
            if($numberOfResults & 1)
            {
                $numberOfResults = $numberOfResults - 1;
            }
            $numberOfResults = $numberOfResults / 2;
            $urlApache = "http://stuweb.cms.gre.ac.uk/~lj231/WebServicesApache/Service/Services/GetAdvertsBySearch.php?term=$searchTerms&postcode=$postcode&distance=$distance&resultsorder=$resultsOrder&numberofresults=$numberOfResults";
            $apacheResult = new DOMDocument(); 
            $apacheResult->load($urlApache);
            $urlIis = "http://stuiis.cms.gre.ac.uk/lj231/SOA/WebServicesIIS.Service/ItemSearch.asmx/GetAdvertsBySearch?searchTerm=$keywords&postcode=$postcode&distance=$distance&resultsorder=$resultsOrder&numberofresults=$numberOfResults";
            $iisResult = new DOMDocument(); 
            $iisResult->load($urlIis);

            $xmlRoot = $iisResult->documentElement; 
            foreach ( $apacheResult->documentElement->childNodes as $apacheNode ) 
            { 
               $iisNode = $iisResult->importNode($apacheNode,true); 
               $xmlRoot->appendChild($iisNode); 
            }

            return $iisResult->saveXML(); 
        }

    public function createAdvertHtml($advertId, $title, $description, $insertedStamp, $location)
    {
        $results = $this->getImages($advertId, $location);
        $images = new DOMDocument();
        $images->loadXML($results);
        try
            {
             $firstImage = $images->getElementsByTagName('Location')->item(0)->nodeValue;
            } catch(Exception $e)
            {
            }
       
        if($firstImage)
        {
        $firstImage = "http://stu-nginx.cms.gre.ac.uk/~lj231$firstImage";
        } else
        {
        $firstImage = '../Image/no-image.gif';
        }
        date_default_timezone_set('Europe/London');
        $insertedStamp = new \DateTime($insertedStamp);
        $date = date_format($insertedStamp, 'd/m/y');
        $time = date_format($insertedStamp, 'g:i A');
        $link = "../Controller/ViewAdvert.php?advertId=$advertId&location=$location";
        $linkTooltip = 'Click to view advert';
        return "
        <div class=\"search-result row\">
            <div class=\"col-xs-12 col-sm-12 col-md-3\">
                 <a href=\"#\" title=\"$title\" class=\"thumbnail\"><img src=\"$firstImage\" alt=\"User uploaded image of $title\" /></a>
            </div>
            <div class=\"col-xs-12 col-sm-12 col-md-2\">
                <ul class=\"meta-search\">
                    <li><i class=\"glyphicon glyphicon-calendar\"></i> <span>$date</span></li>
                    <li><i class=\"glyphicon glyphicon-time\"></i> <span>$time</span></li>
                </ul>
            </div>
            <div class=\"col-xs-12 col-sm-12 col-md-7 excerpet\">
                <h3><a href=\"$link\" title=\"$linkTooltip\">$title</a></h3>
                <p>$description</p>
                <p>$location</p>
            </div>
            <span class=\"clearfix borda\"></span>
        </div>";
    }

    public function getImageHtml($advertId, $location, $advertTitle, $removable)
    {
     $advertManagement = new AdvertManagement;
     $results = $advertManagement->GetImages($advertId, $location);
     $images = new DOMDocument();
     $images->loadXML($results);
     $advertManagement = new AdvertManagement;
     foreach ($images->getElementsByTagName('Image') as $image)
        {
            $imageId = $image->getElementsByTagName('ImageId')->item(0)->nodeValue;
            $location = $image->getElementsByTagName('Location')->item(0)->nodeValue;
            if($removable)
            {
                $removeLink = "<a href=\"../Controller/RemoveConfirm.php?advertId=$advertId&amp;imageId=$imageId\">Remove image</a>";
            }
            $html = $html . "<div class=\"col-xs-12 col-sm-12 col-md-3\">
                                <a href=\"#\" title=\"$advertTitle\" class=\"thumbnail\"><img src=\"http://stu-nginx.cms.gre.ac.uk/~lj231$location\" alt=\"User uploaded image of $advertTitle\" /></a>
                                $removeLink
                             </div>";
        }
    return $html;
    }

    private function insertAdvert($title, $description, $userId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('insert into Adverts (Title, Description, InsertedStamp, UserId) values (?, ?, ?, ?)');
        $statement->bindValue(1, $title, \PDO::PARAM_STR);
        $statement->bindValue(2, $description, \PDO::PARAM_STR);
        $statement->bindValue(3, date("Y-m-d H:i:s"), \PDO::PARAM_STR);
        $statement->bindValue(4, $userId, \PDO::PARAM_INT);
        $database = new Database;
        $database->insert($statement);
        return $connection->lastInsertId();
    }

    private function getAdvertsByUserId($userId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Adverts where UserId = ?');
        $statement->bindValue(1, $userId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement);
    }

    private function insertImageLocations($advertId, $images)
    {
        foreach ($images as $image)
        {
            $connection = ConnectionFactory::getFactory()->getConnection();
            $statement = $connection->prepare('insert into Images (Location, AdvertId) values (?, ?)');
            $statement->bindValue(1, $image, \PDO::PARAM_STR);
            $statement->bindValue(2, $advertId, \PDO::PARAM_INT);
            $database = new Database;
            $database->insert($statement);
        }
    }
}

?>