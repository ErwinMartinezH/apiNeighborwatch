<?php
$servername = "bctr1cqyexg63exbsbkl-mysql.services.clever-cloud.com";
$username = "uy11uut7gmgicdsn";
$password = "kHeJuuqSY0pt7vErLyI9";
$dbname = "bctr1cqyexg63exbsbkl";
$port = 3306;

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
