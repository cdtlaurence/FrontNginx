<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Business\UserManagement;
use FrontNginx\Web\Model\TemplateParameter;
use FrontNginx\Web\Model\Enum\AccountStatus;
use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Utilities\SessionManagement;

main();

function main()
{
    $userManagement = new UserManagement;
    if ($userManagement->isLoggedIn())
    {    
        $sessionManagement = new SessionManagement;
        $user = $sessionManagement->get('user'); 
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $title = $_POST['titleInput'];
            $firstname = $_POST['firstNameInput'];
            $lastname = $_POST['lastNameInput'];
            $postcode = $_POST['postcodeInput'];
            $userManagement->updateCurrentUser($title, $firstname, $lastname, $postcode);
            header('Location: MyProfile.php');
            exit;
        }
        elseif($user->AccountStatusId == AccountStatus::ReadyToPost)
        {
            $menuBar = createMenuBar(true);
        } else
        {
            $menuBar = createMenuBar(false);
        }
            $title = new TemplateParameter('title', $user->Title);
            $firstname = new TemplateParameter('firstname', $user->FirstName);
            $lastname = new TemplateParameter('lastname', $user->LastName);
            $address = $userManagement->getAddress($user->AddressId);
            $postcode = new TemplateParameter('postcode', $address->PostCode);
            outputPage($menuBar, $title, $firstname, $lastname, $postcode);
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

function createMenuBar($CanPost)
{
if($CanPost)
{
    $menuBar = '<ul class="nav nav-tabs">
                    <li class="active"><a href="../Controller/MyProfile.php">Profile</a></li>
                    <li><a href="../Controller/Messages.php">Messages</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="../Controller/MyAdverts.php">My Adverts<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../Controller/AddAdvert.php">Add Advert</a></li>
                        </ul>
                    </li>
                 </ul>';
    return new TemplateParameter('menuBar', $menuBar);
} else
{
    $menuBar =  '<ul class="nav nav-tabs">
                    <li class="active"><a href="../Controller/MyProfile.php">Profile</a></li>
                </ul>';
    return new TemplateParameter('menuBar', $menuBar);
}
}

function outputPage($menuBar, $title, $firstname, $lastname, $postcode)
{
    $templateParameters = array($menuBar, $title, $firstname, $lastname, $postcode);
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('myprofile.html', $templateParameters);
}

?>