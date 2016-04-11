<?php

namespace FrontNginx\Web\Utilities;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

class AddressApiManagement
{
     public function getAddress($postcode)
     {
        $service_url = "https://api.postcodes.io/postcodes/" . $postcode;
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return json_decode($curl_response, true);
     }

     //This code is from http://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php 
     public function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
     {
          // convert from degrees to radians
          $latFrom = deg2rad($latitudeFrom);
          $lonFrom = deg2rad($longitudeFrom);
          $latTo = deg2rad($latitudeTo);
          $lonTo = deg2rad($longitudeTo);

          $lonDelta = $lonTo - $lonFrom;
          $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
          $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

          $angle = atan2(sqrt($a), $b);
          return $angle * $earthRadius;
     }
}

?>