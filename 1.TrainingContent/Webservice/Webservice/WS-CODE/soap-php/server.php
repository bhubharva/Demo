<?php
#ini_set("soap.wsdl_cache_enabled", "0");
class TestSoapServer
{
	var $meetings = array();
	function __construct()
	{
		$this->addMeeting("Office 102","Meeting With Bob","Medium",1000,30);
		$this->addMeeting("Meeting Room 2","Project Review","High",1100,60);
		$this->addMeeting("Somewhere","A Meeting...","Medium",1230,50);
		$this->addMeeting("Undisclosed Location","Shady Meeting","High",2300,60);
	}
	function addMeeting($Location, $Name, $Importance, $StartTime, $Duration)
	{
		$meeting = new Meeting();
		$meeting->Location = $Location;
		$meeting->Name = $Name;
		$meeting->Importance = $Importance;
		$meeting->StartTime = $StartTime;
		$meeting->Duration = $Duration;
		$this->meetings[] = $meeting;
	}
	function GetNextMeeting()
	{
		$time = (idate("H")*100)+idate("i"); // Format time as HHMM (12:34 -> 1234)
		foreach ($this->meetings as $key => $current)
		{
			if ($current->StartTime > $time)
			{
				return array($current);
			}
		}
		return array($this->meetings[0]);
	}
	function GetTodaysMeetings()
	{
		return $this->meetings;
	}
}

class Meeting
{
	var $Location;
	var $Name;
	var $Importance;
	var $StartTime;
	var $Duration;
}

$classmap = array('Meeting'=>'Meeting');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$server = new SoapServer('soap.wsdl',array('classmap'=>$classmap));
	$server->setClass("TestSoapServer");
	$server->handle();
}
else
{
?>
<html><head><title>PHP Soap Server</title></head>
<body>
	<h2>Hi There!</h2>
	<p>This is a PHP based SOAP demonstration server for the tutorial at </p>
	<p>The client demo script is here : <a href="client.php">client.php</a></p>
</body></html>
<?php
}
?>
