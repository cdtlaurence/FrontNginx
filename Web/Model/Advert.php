<?php

namespace FrontNginx\Web\Model;

class Advert
{
    public $name;
    public $content;

    public function __construct($inputName, $inputContent = '')
    {
        $this->name = $inputName;
        $this->content = $inputContent;
    }
}

?>