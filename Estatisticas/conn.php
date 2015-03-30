<?php
require_once("functions_filtros.php");
chdir("..");
include_once "../com/comum.php";
chdir("Estatisticas");
$tsdia=date("Y-m-d H:i:s");

$conn = sqlsrv_connect(serverName, array("Database"=>dbName, "UID"=>user, "PWD"=>pass));
if( !$conn ) {
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>