<?php

    $file = 'testcron.txt';
    $text = "Executed on: " . date("d/m/Y h:i:s A");
    $text = $text.' - '.$error . "\n";
    
    
    file_put_contents($file, $text, FILE_APPEND);
    
?>