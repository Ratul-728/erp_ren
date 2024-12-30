<?php
require "common/conn.php";

session_start();

$key = '__b81f406b-901d-4bf2-bce1-45ebbedafeb2__';
$holiday_api = new\HolidayAPI\Client(['key' => $key]);
$holidays = $holiday_api->holidays([
  'country' => 'BD-C',
  'year' => '2021',
]);


?>