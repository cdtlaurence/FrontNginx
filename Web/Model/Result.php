<?php

namespace FrontNginx\Web\Model;

class Result
{
    public $worked;
    public $errorCode;

    public function __construct($inputWorked, $errorCode = null)
    {
        $this->worked = $inputWorked;
        $this->errorCode = $errorCode;
    }
}

?>