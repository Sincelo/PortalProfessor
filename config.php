<?php
if(is_file("MdpSettings.config")) {
	$xml=simplexml_load_file("MdpSettings.config");
	for($i=0; $i<$xml->add->count();$i++) {
		if($xml->add[$i]["key"]=="ServerD") define("serverName", $xml->add[$i]["value"]);
		elseif($xml->add[$i]["key"]=="DBD") define("dbName", $xml->add[$i]["value"]);
	}
} else {
	$xml=simplexml_load_file("Web.config");
	for($i=0; $i<$xml->appSettings->add->count();$i++) {
		if($xml->appSettings->add[$i]["key"]=="ServerD") define("serverName", $xml->appSettings->add[$i]["value"]);
		elseif($xml->appSettings->add[$i]["key"]=="DBD") define("dbName", $xml->appSettings->add[$i]["value"]);
	}
}
define("user", "PAAEAdmin");
define("pass", "aPAAEpmBD05");
define("AnoLetivo",date("Y",time()-20995200));
if(substr($_SERVER["COMPUTERNAME"],-4)=="-SCL") $_SESSION["debug"]=true;
?>