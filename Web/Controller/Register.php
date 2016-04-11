<?php

namespace FrontNginx\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Business\UserManagement;
use FrontNginx\Web\Model\Result;
use FrontNginx\Web\Model\TemplateParameter;
use FrontNginx\Web\Model\Enum\ErrorCode;
use FrontNginx\Web\Utilities\PageManagement;
use FrontNginx\Web\Utilities\ReCaptchaApiManagement;
use FrontNginx\Web\Utilities\Validation;

main();

function main()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (!$_POST['g-recaptcha-response'])
        {
         $errorMessage = 'Please complete the reCAPTCHA';
         outputPage($errorMessage);
         exit;
        }
        $reCaptchaApiManagement = new ReCaptchaApiManagement;
        if (!($reCaptchaApiManagement->IsUserOkay($_POST['g-recaptcha-response'])))
        {
         $errorMessage = 'Please complete the reCAPTCHA correctly';
         outputPage($errorMessage);
         exit;
        }
        $username = trim($_POST['usernameInput']);
        $password = trim($_POST['passwordInput']);
        $email = trim($_POST['emailInput']);
        $validation = new Validation;
        $result = $validation->validateRegister($username, $email);
        if(!$result)
        {
            $registerErrorMessage = 'Please check all fields are filled out correctly.';
            outputPage($registerErrorMessage);
            exit;
        }
        $userManagement = new UserManagement();
        $result = $userManagement->register($username, $password, $email);
        if($result->worked)
        {
            header("Location: Activation.php?username=$username");
            exit;
        } else
        {
            switch ($result->errorCode) 
            {
                case ErrorCode::UsernameTaken:
                        $usernameInputErrorMessage = 'Sorry, that username has already been taken. Please choose another.';
                        break;
                outputPage($usernameInputErrorMessage);
                exit;
            }
        }
    }
    outputPage();
    exit;
}

function outputPage($registerErrorMessage = '')
{
    $templateParameters = array(new TemplateParameter('registerErrorMessage', $registerErrorMessage));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('register.html', $templateParameters);
}

?>