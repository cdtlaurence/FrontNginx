<?php

namespace FrontNginx\Web\Utilities;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

class ReCaptchaApiManagement
{
     public function IsUserOkay($token)
     {
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfYFBATAAAAALbQZyJIPpv-23QBH8Aak-fTAK_n&response=".$token, true);
        return $response['success'];
     }
}

?>