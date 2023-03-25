<?php
    require 'vendor/autoload.php';
    
    $client = new MongoDB\Client("mongodb://0.0.0.0:27017");
    $citizens = $client->registry->citizens;
?>