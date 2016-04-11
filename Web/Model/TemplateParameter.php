<?php

namespace FrontNginx\Web\Model;

class TemplateParameter
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