<?php

namespace FrontNginx\Web\Utilities;

use FrontNginx\Web\Utilities\SessionManagement;

class Validation
{
    public function validateUsername($username)
    {
    }

    public function validatePassword($password)
    {
    }

    public function validateEmail($email)
    {
    }

    public function validateRegister($username, $email)
    {
    if(($username == '') || ($email == '') || ($username==$email))
    {
        return FALSE;
    } else
    {
        return TRUE;
    }
    }

    public function validateAdvert($title, $description)
    {
    if(($title == '') || ($description == ''))
    {
        return FALSE;
    } else
    {
        return TRUE;
    }
    }

    public function validateImages($images)
    {
        $valid_formats = array('jpg', 'png', 'gif', 'bmp');
        $max_file_size = 1024*3000; //3000 kb
        $path = '/ImageUploads/'; // Upload directory
        $okayImages=array();
        foreach ($images['name'] as $f => $name) 
        {     
            if ($images['error'][$f] == 4) 
            {
            //echo 'jaems1';
                continue; // Skip file if any error found
            }	       
            if ($images['error'][$f] == 0) 
            {	           
                if ($images['size'][$f] > $max_file_size) 
                {
                        //    echo 'jaems2';
                    continue; // Skip large files
                }
                elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) )
                {
                        //    echo 'jaems3';
                    continue; // Skip invalid file formats
                }
                else
                {
                    $pathAndName = $path.date('Y-m-d H:i:s').$name;
                    if (move_uploaded_file($images['tmp_name'][$f], dirname(dirname(dirname(__DIR__))).$pathAndName))
                    {
                        array_push($okayImages, $pathAndName);
                       // echo "Uploaded";
                    }
                }
            }
        }
        return $okayImages;
    }
}

?>