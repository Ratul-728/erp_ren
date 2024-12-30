<?php
  

function createDeviceFingerprint()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    $fingerprint = $userAgent . $acceptLanguage . $ipAddress;

    return md5($fingerprint);
}

echo createDeviceFingerprint();

echo "<hr>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

?>
    
    
