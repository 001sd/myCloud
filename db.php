<?php
$dbhost = "localhost";
$dbuser = "srv111841_z5"; 
$dbpass = "zaq1@WSX";     
$dbname = "srv111841_z5";    

$link = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($link->connect_error) {
    die("Błąd połączenia z bazą danych: " . $link->connect_error);
}
?>