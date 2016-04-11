<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Model\TemplateParameter;
use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Model\Enum\ErrorCode;

main();

function main()
{
    switch ($_GET['errorCode']) 
        {
            case ErrorCode::PasswordIncorrect:
                $error = 'Sorry, there is something wrong with your login details. Please check them and try again.';
                outputPage($error);
                exit();
            case ErrorCode::UserNotActivated:
                $error = 'Please activate your account and then try again';
                outputPage($error);
                exit();
        }
    outputPage();
}

function outputPage($error)
{
    $templateParameters = array(new TemplateParameter('errorMessage', $error));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('error.html', $templateParameters);
}

?>