<?php

//$server_location = "http://www.xnet.tk/test-soap/server.php";
$server_location = "http://localhost/soap-php/server.php";
$client = new SoapClient("soap.wsdl",array('location' => $server_location));

echo "<h2>Next Meeting</h2><pre>";
$result = $client->GetNextMeeting();
var_dump($result);
echo "</pre>";

echo "<h2>Todays Meetings</h2><pre>";
$result = $client->GetTodaysMeetings();
var_dump($result);
echo "</pre>";
?>

