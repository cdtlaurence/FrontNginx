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
        $advertId = $_GET['advertId'];
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
        $advertManagement->removeAdvert($advertId);
        header('Location: MyAdverts.php');
        exit;
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

?>