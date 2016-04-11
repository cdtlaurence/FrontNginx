<?php

namespace FrontNginx\Web\Utilities;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use FrontNginx\Web\Utilities\SessionManagement;

class PageManagement
{
    public function handlePage($template, $templateParameters)
    {
        $html = file_get_contents('../View/' . $template);
        $html = str_replace('{{topNav}}', $this->getTopNav(), $html);
        if (is_null($templateParameters)) return $html;
        foreach ($templateParameters as $templateParameter) 
        {
            $html = str_replace("{{{$templateParameter->name}}}", $templateParameter->content, $html);
        }
        return $html;
    }

    private function getTopNav()
    {
        $sessionManagement = new SessionManagement;
        $user = $sessionManagement->get('user');
        if(!is_null($user))
        {
            $html = file_get_contents('../View/topnavloggedin.html');
            return str_replace('{{username}}', $user->Username, $html);
        }
        else
        {
            return file_get_contents('../View/topnavloggedout.html');
        }
    }
}

?>