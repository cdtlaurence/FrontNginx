<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Business\UserManagement;
use FrontNginx\Web\Model\TemplateParameter;
use FrontNginx\Web\Model\Enum\ErrorCode;
use FrontNginx\Web\Utilities\PageManagement;

main();

function main()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $username = trim($_POST['usernameInput']);
        $code = trim($_POST['codeInput']);
        $userManagement = new UserManagement();
        $result = $userManagement->activate($username, $code);
        if($result->worked)
        {
            $userManagement->loginWithoutValidation($username);
            header('Location: MyProfile.php');
            exit;
        } else
        {
            switch ($result->errorCode) 
            {
                case ErrorCode::UsernameNotFound:
                     $errorMessage = 'Sorry, that username cannot be found in our database. Please check it and try again.';
                     break;
                case ErrorCode::ActivationCodeIncorrect:
                     $errorMessage = 'Sorry, that activation code was incorrect. Please check it and try again';
                     break;
                outputPage($errorMessage);
                exit;
            }
            outputPage('','', $errorMessage);
            exit;
        }
    }
    outputPage($_GET['username'], $_GET['activationCode'], '');
}

function outputPage($username = '', $activationCode = '', $errorMessage = '')
{
    $templateParameters = array(new TemplateParameter('activationCode', $activationCode), new TemplateParameter('username', $username), new TemplateParameter('errorMessage', $errorMessage));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('activation.html', $templateParameters);
}

?>