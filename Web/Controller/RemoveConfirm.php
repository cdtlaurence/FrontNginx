<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $imageId = $_POST['removeImageIdHidden'];
                $advertId = $_POST['removeAdvertIdHidden'];
                $advertManagement = new AdvertManagement;
                $advertManagement->RemoveImage($imageId);
                header("Location: EditAdvert.php?advertId=$advertId");
                exit;
            }
        $advertId = $_GET['advertId'];
        $imageId = $_GET['imageId'];
        if(!$advertId)
        {
            header('Location: MyAdverts.php');
            exit;
        }
        $advertManagement = new AdvertManagement;
        $advert = $advertManagement->GetAdvert($advertId);
        if ($advert->UserId != $user->UserId)
        {
            header('Location: MyAdverts.php');
            exit;
        }
        $imageHtml = new TemplateParameter('imageHtml', getImageHtml($imageId, $advert));
        $removeAdvertId = new TemplateParameter('removeAdvertId', $advertId);
        $removeImageId = new TemplateParameter('removeImageId', $imageId);
        outputPage(array($imageHtml, $removeAdvertId, $removeImageId));
        exit;
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

function getImageHtml($imageId, $advert)
{
    $advertManagement = new AdvertManagement;
    $image = $advertManagement->GetImage($imageId);
    return "<div class=\"col-xs-12 col-sm-12 col-md-3\">
                <a href=\"#\" title=\"$advert->Title\" class=\"thumbnail\"><img src=\"../../..$image->Location\" alt=\"User uploaded image of $advert->Title\" /></a>
            </div>";
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('removeconfirm.html', $templateParameters);
}

?>