<?php

namespace FrontNginx\Web\Utilities;

function autoload($className)
{
    $newClassName = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $filename = dirname(dirname(dirname(__DIR__))) . '/' .$newClassName . ".php";
    if (is_readable($filename))
    {
        require $filename;
    }
}

spl_autoload_register('FrontNginx\Web\Utilities\autoload');

?>