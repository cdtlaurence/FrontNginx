<?php

namespace FrontNginx\Web\Utilities;

ini_set("sendmail_from","lj231@gre.ac.uk");

class Email
{
    private $address;
    private $subject;
    private $message;

    public function __construct($inputAddress, $inputSubject, $inputMessage)
    {
        $this->address = $inputAddress;
        $this->subject = $inputSubject;
        $this->message = $inputMessage;
    }

    public function send()
    {
        try 
        {
            mail($this->address, $this->subject, $this->message);
        }
        catch(Exception $ex)
        {
            print($ex->getMessage());
        }
    }
}

?>
