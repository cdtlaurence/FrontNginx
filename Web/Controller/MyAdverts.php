<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Business\UserManagement;
use FrontNginx\Web\Business\AdvertManagement;
use FrontNginx\Web\Model\TemplateParameter;
use FrontNginx\Web\Model\Enum\AccountStatus;
use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Utilities\SessionManagement;

main();

function main()
{
    $sessionManagement = new SessionManagement;
    $user = $sessionManagement->get('user');
    if ($user->AccountStatusId == AccountStatus::ReadyToPost)
    {
        $advertManagement = new AdvertManagement;
        $adverts = $advertManagement->getAdverts($user->UserId);
        $searchResults = createSearchResults($adverts);
        outputPage($searchResults);
        exit;
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

function createSearchResults($adverts)
{
     $advertManagement = new AdvertManagement;
     foreach ($adverts as $advert)
     {
     $html = $html . $advertManagement->createAdvertHtml($advert, true);
     }
    return array(new TemplateParameter('searchResults', $html));
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('myadverts.html', $templateParameters);
}

?>